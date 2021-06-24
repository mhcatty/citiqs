<?php
    declare(strict_types=1);

    require_once APPPATH . 'interfaces/InterfaceCrud_model.php';
    require_once APPPATH . 'interfaces/InterfaceValidate_model.php';
    require_once APPPATH . 'abstract/AbstractSet_model.php';

    if (!defined('BASEPATH')) exit('No direct script access allowed');

    Class Customeremail_model extends AbstractSet_model implements InterfaceCrud_model, InterfaceValidate_model
    {

        public $id;
        public $vendorId;
        public $email;
        public $active;

        private $table = 'tbl_customer_emails';

        protected function setValueType(string $property,  &$value): void
        {
            $this->load->helper('validate_data_helper');
            if (!Validate_data_helper::validateNumber($value)) return;

            if ($property === 'id' || $property === 'vendorId') {
                $value = intval($value);
            }

            return;
        }

        protected function getThisTable(): string
        {
            return $this->table;
        }

        public function insertValidate(array $data): bool
        {
            if (isset($data['vendorId']) && isset($data['email'])) {
                return $this->updateValidate($data);
            }
            return false;
        }

        public function updateValidate(array $data): bool
        {
            if (!count($data)) return false;
            if (isset($data['vendorId']) && !Validate_data_helper::validateInteger($data['vendorId'])) return false;
            if (isset($data['email']) && !Validate_data_helper::validateEmail($data['email'])) return false;            
            if (isset($data['active']) && !($data['active'] === '1' || $data['active'] === '0')) return false;
            return true;
        }

        public function insertEmails(array $data): bool
        {
            $keys = '';
            $allValues = [];

            foreach($data as $insert) {
                if (!$this->insertValidate($insert)) continue;
                if (empty($keys)) $keys = array_keys($insert);

                $values = array_values($insert);
                $escapeValues = array_map(function($value) {
                    return $this->db->escape($value);
                }, $values);
                array_push($allValues, '(' . implode(',', $escapeValues) . ')');;
            }

            if (!$keys || empty($allValues)) return false;

            $query =  '';
            $query  = 'INSERT INTO ' . $this->getThisTable() . ' ';
            $query .= '(' . implode(',' , $keys) . ')  ';
            $query .= 'VALUES ';
            $query .= implode(',', $allValues) ;
            $query .= ' ON DUPLICATE KEY UPDATE email = VALUES(email);';

            return $this->db->query($query);
        }

        public function fetchVendorEmails(): ?array
        {
            $where = [
                $this->table . '.vendorId' => $this->vendorId
            ];

            if ($this->id) {
                $where[$this->table . '.id'] = $this->id;
            }

            return $this->readImproved([
                'what' => [$this->table . '.*'],
                'where' => $where
            ]);
        }

        public function sendEmails(int $checkBreak, string $helperName, string $methodToCall, array $rawArguments): void
        {
            $emails = $this->fetchVendorEmails();

            if (is_null($emails)) return;

            $this->load->helper(strtolower($helperName));

            $sent = 0;

            while ($emails) {
                $data = array_pop($emails);

                if ($data['active'] === '0') continue;

                $arguments = $rawArguments;         
                array_unshift($prepareArguments, $data['email']);

                if (empty(call_user_func_array([ucfirst($helperName), $methodToCall], $arguments))) {
                    $sent++;
                    if ($sent % $checkBreak === 0) sleep(1);
                }
            }
        }

    }
