<?php
    declare(strict_types=1);

    require_once APPPATH . 'interfaces/InterfaceCrud_model.php';
    require_once APPPATH . 'interfaces/InterfaceValidate_model.php';
    require_once APPPATH . 'abstract/AbstractSet_model.php';

    if (!defined('BASEPATH')) exit('No direct script access allowed');

    Class Shopvendor_model extends AbstractSet_model implements InterfaceCrud_model, InterfaceValidate_model
    {
        public $id;
        public $vendorId;
        public $serviceFeePercent;
        public $serviceFeeAmount;
        public $paynlServiceId;
        public $termsAndConditions;
        public $requireMobile;
        public $payNlServiceId;
        public $printTimeConstraint;
        public $minimumOrderFee;
        public $serviceFeeTax;

        public $bancontact;
        public $ideal;
        public $creditCard;


        private $table = 'tbl_shop_vendors';

        protected function setValueType(string $property,  &$value): void
        {
            $this->load->helper('validate_data_helper');
            if (!Validate_data_helper::validateInteger($value)) return;

            if ($property === 'id' || $property === 'vendorId' || $property === 'serviceFeeTax') {
                $value = intval($value);
            }
            if ($property === 'serviceFeePercent' || $property === 'serviceFeeAmount') {
                $value = floatval($value);
            }
            return;
        }

        protected function getThisTable(): string
        {
            return $this->table;
        }

        public function insertValidate(array $data): bool
        {
            if (isset($data['vendorId'])) {
                return $this->updateValidate($data);
            }
            return false;
        }

        public function updateValidate(array $data): bool
        {
            if (!count($data)) return false;
            if (isset($data['vendorId']) && !Validate_data_helper::validateInteger($data['vendorId'])) return false;
            if (isset($data['requireMobile']) && !($data['requireMobile'] === '1' || $data['requireMobile'] === '0')) return false;
            // if (isset($data['termsAndConditions']) && !Validate_data_helper::validateString($data['termsAndConditions'])) return false;
            if (isset($data['serviceFeePercent']) && !Validate_data_helper::validateString($data['serviceFeePercent'])) return false;
            if (isset($data['serviceFeeAmount']) && !Validate_data_helper::validateFloat($data['serviceFeeAmount'])) return false;
            if (isset($data['paynlServiceId']) && !Validate_data_helper::validateString($data['paynlServiceId'])) return false;
            if (isset($data['bancontact']) && !($data['bancontact'] === '1' || $data['bancontact'] === '0')) return false;
            if (isset($data['ideal']) && !($data['ideal'] === '1' || $data['ideal'] === '0')) return false;
            if (isset($data['creditCard']) && !($data['creditCard'] === '1' || $data['creditCard'] === '0')) return false;
            if (isset($data['printTimeConstraint']) && !Validate_data_helper::validateInteger($data['printTimeConstraint'])) return false;
            if (isset($data['minimumOrderFee']) && !Validate_data_helper::validateFloat($data['minimumOrderFee'])) return false;
            if (isset($data['serviceFeeTax']) && !Validate_data_helper::validateInteger($data['serviceFeeTax'])) return false;

            return true;
        }

        public function getVendorData(): ?array
        {

            $filter = [
                'what' => [
                    $this->table . '.id',
                    $this->table . '.serviceFeePercent',
                    $this->table . '.serviceFeeAmount',
                    $this->table . '.payNlServiceId',
                    $this->table . '.termsAndConditions',
                    $this->table . '.requireMobile',
                    $this->table . '.bancontact',
                    $this->table . '.ideal',
                    $this->table . '.creditCard',
                    $this->table . '.printTimeConstraint',
                    $this->table . '.minimumOrderFee',
                    $this->table . '.serviceFeeTax',
                    'tbl_user.id AS vendorId',
                    'tbl_user.username AS vendorName',
					'tbl_user.logo AS logo',
                    'tbl_user.email AS vendorEmail'

                ],
                'where' => [
                    $this->table. '.vendorId' => $this->vendorId,
                ],
                'joins' => [
                    ['tbl_user', 'tbl_user.id = ' . $this->table .'.vendorId' , 'INNER']
                ]
            ];
            // var_dump($filter);
            // die();

            $result = $this->readImproved($filter);

            if (is_null($result)) return null;
            $result = reset($result);

            $result['serviceFeePercent'] = floatval($result['serviceFeePercent']);
            $result['serviceFeeAmount'] = floatval($result['serviceFeeAmount']);
            $result['minimumOrderFee'] = floatval($result['minimumOrderFee']);
            $result['serviceFeeTax'] = intval($result['serviceFeeTax']);
            $result['printTimeConstraint'] = intval($result['printTimeConstraint']);
            $result['vendorId'] = intval($result['vendorId']);
            return $result;
        }

        public function getPrintTimeConstraint()
        {
            $printTimeConstraint = $this->shopvendor_model->readImproved([
                'what' => ['printTimeConstraint'],
                'where' => ['vendorId' => $this->vendorId]
            ]);

            $printTimeConstraint = reset($printTimeConstraint)['printTimeConstraint'];
            return date('Y-m-d H:i:s', strtotime( '-' . $printTimeConstraint . ' hours', time() ));
        }

        public function getVendors(array $where): ?array
        {

            $filter = [
                'what' => [
                    $this->table . '.id',
                    $this->table . '.serviceFeePercent',
                    $this->table . '.serviceFeeAmount',
                    $this->table . '.payNlServiceId',
                    $this->table . '.termsAndConditions',
                    $this->table . '.requireMobile',
                    $this->table . '.bancontact',
                    $this->table . '.ideal',
                    $this->table . '.creditCard',
                    $this->table . '.printTimeConstraint',
                    $this->table . '.minimumOrderFee',
                    'tbl_user.id AS vendorId',
                    'tbl_user.username AS vendorName',
					'tbl_user.logo AS logo',
                    'tbl_user.email AS vendorEmail'

                ],
                'where' => $where,
                'joins' => [
                    ['tbl_user', 'tbl_user.id = ' . $this->table .'.vendorId' , 'INNER']
                ]
            ];

            return $this->readImproved($filter);
        }
    }
