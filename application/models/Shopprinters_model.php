<?php
    declare(strict_types=1);

    require_once APPPATH . 'interfaces/InterfaceCrud_model.php';
    require_once APPPATH . 'interfaces/InterfaceValidate_model.php';
    require_once APPPATH . 'abstract/AbstractSet_model.php';

    if (!defined('BASEPATH')) exit('No direct script access allowed');

    Class Shopprinters_model extends AbstractSet_model implements InterfaceCrud_model, InterfaceValidate_model
    {
        public $id;
        public $userId;
        public $printer;
        public $macNumber;
        public $active;
        public $numberOfCopies;
        public $masterMac;
        private $table = 'tbl_shop_printers';

        protected function setValueType(string $property,  &$value): void
        {
            $this->load->helper('validate_data_helper');
            if (!Validate_data_helper::validateInteger($value)) return;

            if ($property === 'id' || $property === 'userId' || $property === 'numberOfCopies') {
                $value = intval($value);
            }
            if ($property === 'price') {
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
            if (isset($data['userId']) && isset($data['printer']) && isset($data['macNumber'])) {
                return $this->updateValidate($data);
            }
            return false;
        }

        public function updateValidate(array $data): bool
        {
            if (!count($data)) return false;
            if (isset($data['userId']) && !Validate_data_helper::validateInteger($data['userId'])) return false;
            if (isset($data['printer']) && !Validate_data_helper::validateString($data['printer'])) return false;
            if (isset($data['macNumber']) && !Validate_data_helper::validateString($data['macNumber'])) return false;
            if (isset($data['active']) && !($data['active'] === '1' || $data['active'] === '0')) return false;
            if (isset($data['numberOfCopies']) 
                && (!Validate_data_helper::validateInteger($data['numberOfCopies']) || intval($data['numberOfCopies']) < 1)
            ) return false;
            if (isset($data['masterMac']) && !Validate_data_helper::validateString($data['masterMac'])) return false;

            return true;
        }

        public function fetchtProductPrinters(int $productId): ?array
        {
            return $this->read(
                [
                    $this->table . '.id AS printerId',
                    $this->table . '.printer AS printer',
                    $this->table . '.active AS printerActive',
                ],
                ['tbl_shop_product_printers.productId=' => $productId],
                [
                    ['tbl_shop_product_printers', $this->table .'.id = tbl_shop_product_printers.printerId' ,'INNER']
                ]
            );
        }

        public function checkPrinterName(array $where): bool
        {
            $filter = [
                'what'  => ['id'],
                'where' => $where,
            ];
            return $this->readImproved($filter) ? true : false;
        }

        public function fetchPrinters(): ?array
        {
            $query =
            '
                SELECT
                    tbl_shop_printers.*,
                    masters.printer as master
                FROM
                    tbl_shop_printers
                LEFT JOIN
                    (SELECT tbl_shop_printers.id, tbl_shop_printers.printer, tbl_shop_printers.masterMac, tbl_shop_printers.macNumber FROM tbl_shop_printers) masters ON masters.macNumber = tbl_shop_printers.masterMac
                WHERE
                    tbl_shop_printers.userId = ' . $this->userId . '
                ORDER BY tbl_shop_printers.printer ASC;
            ';

            $result = $this->db->query($query);
            $result = $result->result_array();
            return $result ? $result : null;
        }

        public function printMacNumber()
        {
            $masterMac = $this->readImproved([
                'what' => ['masterMac'],
                'where' => [
                    'macNumber' => $this->macNumber
                ]
            ]);

            if (empty($masterMac)) {
                return $this->macNumber;
            }

            $macNumber = $masterMac[0]['masterMac'];
            return $macNumber === '0' ? $this->macNumber : $macNumber;
        }
    }
