<?php
    require_once APPPATH . '/libraries/koolreport/core/autoload.php';

    use \koolreport\processes\Filter;
    use \koolreport\processes\ColumnMeta;
	use \koolreport\processes\CalculatedColumn;
    use \koolreport\pivot\processes\Pivot;
    use \koolreport\processes\Group;
    use \koolreport\processes\Sort;
    use \koolreport\processes\Limit;
	use \koolreport\codeigniter\Friendship;

    class Vatreport extends \koolreport\KoolReport
    {
		use \koolreport\inputs\Bindable;
		use \koolreport\inputs\POSTBinding;


		protected function settings()
        {
            return array(
                'dataSources' => [
                    'alfred' => [
                        'connectionString' => 'mysql:host=' . HOSTNAME . ';dbname=' . DATABASE,
                        'username' => USERNAME,
                        'password' => PASSWORD,
                        'charset' => 'utf8'
                    ]
                ]
            );
        }

		protected function defaultParamValues()
		{
			return array(
				"dateRange"=>array(date("Y-m-d"),date("Y-m-d")),
				"dateRangeVAT"=>array(date("Y-m-d"),date("Y-m-d")),
			);
		}

		protected function bindParamsToInputs()
		{
			return array(
				"dateRange"=>"dateRange",
				"dateRangeVAT"=>"dateRangeVAT",
			);
		}

        public function setup()
        {
			$this
				->src('alfred')
				->query('
						SELECT
							tbl_shop_orders.id AS orderId, 
							tbl_shop_orders.amount AS orderAmount, 
							tbl_shop_orders.paid AS orderPaidStatus, 
							tbl_shop_orders.created AS createdAt, 
							tbl_shop_categories.category AS category, 
							buyer.id AS buyerId, 
							buyer.email AS buyerEmail, 
							buyer.username AS buyerUserName, 
							vendor.username AS vendorUserName, 
							tbl_shop_order_extended.quantity AS productQuantity, 
							tbl_shop_products_extended.`name` AS productName, 
							tbl_shop_products_extended.price AS productPrice, 
							tbl_shop_products_extended.vatpercentage AS productVat, 
							tbl_shop_spots.spotName AS spotName,
							tbl_shop_products_extended.price * tbl_shop_order_extended.quantity AS productlinetotal,
							((tbl_shop_products_extended.price * tbl_shop_order_extended.quantity) * 100)/(tbl_shop_products_extended.vatpercentage+100) AS EXVAT,
							tbl_shop_orders.serviceFee AS servicefee
						FROM
							tbl_shop_orders
							INNER JOIN
							tbl_shop_order_extended
							ON 
								tbl_shop_orders.id = tbl_shop_order_extended.orderId
							INNER JOIN
							tbl_shop_products_extended
							ON 
								tbl_shop_order_extended.productsExtendedId = tbl_shop_products_extended.id
							INNER JOIN
							tbl_shop_products
							ON 
								tbl_shop_products_extended.productId = tbl_shop_products.id
							INNER JOIN
							tbl_shop_categories
							ON 
								tbl_shop_products.categoryId = tbl_shop_categories.id
							INNER JOIN
							(
								SELECT
									*
								FROM
									tbl_user
								WHERE
									roleid = 2
							) AS vendor
							ON 
								vendor.id = tbl_shop_categories.userId
							INNER JOIN
							(
								SELECT
									*
								FROM
									tbl_user
								WHERE
									roleid = 6 OR
									roleid = 2
							) AS buyer
							ON 
								buyer.id = tbl_shop_orders.buyerId
							INNER JOIN
							tbl_shop_spots
							ON 
								tbl_shop_orders.spotId = tbl_shop_spots.id
						WHERE
							vendor.id = :vendorId AND
							tbl_shop_orders.paid = \'1\' AND 
							tbl_shop_orders.created >= :start AND 
							tbl_shop_orders.created <= :end
						GROUP BY
							orderId
						ORDER BY
							tbl_shop_categories.category ASC	

                ')
				->params([
					":vendorId" => $this->params["vendorId"],
					":start"=>$this->params["dateRange"][0],
					":end"=>$this->params["dateRange"][1]
				])

				->pipe(new CalculatedColumn(array(
					"totalamount"=>"{orderAmount}+{servicefee}"
				)))

				->pipe(new CalculatedColumn(array(
					"totalvatamount"=>"{productlinetotal}-{EXVAT}"
				)))

//				->pipe(new Filter(array(
//					array("createdAt",">=",$this->params["dateRange"][0]),
//					array("createdAt","<=",$this->params["dateRange"][1]),
//				)))

				->pipe(new Group(array(
					"by"=>array("category"),
					"sum"=>array("orderAmount", "servicefee", "totalamount", "totalvatamount" )
				)))

				->pipe($this->dataStore('VATcategory'))
			;

			$this
				->src('alfred')
				->query('
						SELECT
							tbl_shop_products_extended.vatpercentage AS productVat, 
                            tbl_shop_products_extended.`name` AS productName,
                            tbl_shop_products_extended.price,
                            tbl_shop_order_extended.quantity,
                            tbl_shop_products_extended.price * tbl_shop_order_extended.quantity
                            AS AMOUNT,
                            tbl_shop_products_extended.price * tbl_shop_order_extended.quantity * 100 / (tbl_shop_products_extended.vatpercentage+100)
                            AS EXVAT,
							tbl_shop_products_extended.price * tbl_shop_order_extended.quantity 
							-
							tbl_shop_products_extended.price * tbl_shop_order_extended.quantity * 100 / (tbl_shop_products_extended.vatpercentage+100) 
                            AS VAT
                		FROM
							tbl_shop_orders
							INNER JOIN
								tbl_shop_order_extended ON tbl_shop_orders.id = tbl_shop_order_extended.orderId
							INNER JOIN
								tbl_shop_products_extended ON tbl_shop_order_extended.productsExtendedId = tbl_shop_products_extended.id
							INNER JOIN 
                            	tbl_shop_products ON  tbl_shop_products_extended.productId = tbl_shop_products.id
							INNER JOIN
                            
                            	tbl_shop_categories ON  tbl_shop_products.categoryId = tbl_shop_categories.id
							INNER JOIN
							(
                                SELECT
                                *
                                FROM
                                tbl_user
                                WHERE
                                roleid = 2
                            ) AS vendor
							ON 
								vendor.id = tbl_shop_categories.userId
							INNER JOIN
							(
                                SELECT
                                *
                                FROM
                                tbl_user
                                WHERE
                                roleid = 6 OR
                                roleid = 2
                            ) AS buyer
							ON 
								buyer.id = tbl_shop_orders.buyerId
							INNER JOIN
							tbl_shop_spots
							ON 
								tbl_shop_orders.spotId = tbl_shop_spots.id
						WHERE
							vendor.id = :vendorId AND
							tbl_shop_orders.paid = \'1\' AND
							tbl_shop_orders.created >= :start AND 
							tbl_shop_orders.created <= :end

                ')
				->params([
					":vendorId" => $this->params["vendorId"],
					":start"=>$this->params["dateRange"][0],
					":end"=>$this->params["dateRange"][1]
				])

				->pipe(new Group(array(
					"by"=>array("productVat"),
					"sum"=>array("AMOUNT", "VAT", "EXVAT")
				)))

				->pipe($this->dataStore('VATcategorypervatcode'))
			;

			$this // Payment types
				->src('alfred')
				->query('
						SELECT
							tbl_shop_orders.paymentType AS paymentType,
							tbl_shop_orders.amount AS orderTotalAmount,
						 	tbl_shop_orders.serviceFee AS serviceFeeTotalAmount
						FROM
							tbl_shop_orders
						INNER JOIN
							tbl_shop_order_extended ON tbl_shop_orders.id = tbl_shop_order_extended.orderId
						INNER JOIN
							tbl_shop_products_extended ON tbl_shop_order_extended.productsExtendedId = tbl_shop_products_extended.id
						INNER JOIN
							tbl_shop_products ON tbl_shop_products_extended.productId = tbl_shop_products.id
						INNER JOIN
							tbl_shop_categories ON tbl_shop_products.categoryId = tbl_shop_categories.id
						INNER JOIN
							(
								SELECT
									*
								FROM
									tbl_user
								WHERE
									roleid = 2
							) AS vendor
							ON vendor.id = tbl_shop_categories.userId
						INNER JOIN
							tbl_shop_vendors ON tbl_shop_vendors.vendorId = vendor.id
						WHERE
							vendor.id = :vendorId AND
							tbl_shop_orders.paid = \'1\' AND
							tbl_shop_orders.created >= :start AND 
							tbl_shop_orders.created <= :end

                ')
				->params([
					":vendorId" => $this->params["vendorId"],
					":start"=>$this->params["dateRange"][0],
					":end"=>$this->params["dateRange"][1]
				])

				->pipe(new Group(array(
					"by"=>array("paymentType"),
					"sum"=>array("orderTotalAmount", "serviceFeeTotalAmount")
				)))

				->pipe($this->dataStore('Paymentpertype'))
			;

		}
    }



