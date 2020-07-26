<?php
    $tableRows = '';
    $totalOrder = 0;
    $total = 0;
    $quantiy = 0;

    foreach($ordered as $productExtendedId => $data) {
        if (!isset($data['mainProduct'])) {
            $mainExtendedId = $productExtendedId;
        } else {
            $data = $data['mainProduct'][$mainExtendedId ];
        }
        $quantiy = $quantiy + intval($data['quantity'][0]);
        $totalOrder = $totalOrder + floatval($data['amount'][0]);

        $tableRows .= '<tr>';
        $tableRows .=   '<td>' . $data['quantity'][0] . ' x ' .  $data['name'][0] . '</td>';
        $tableRows .=   '<td>' . number_format($data['amount'][0], 2, '.', ',') . ' &euro;</td>';
        $tableRows .= '</tr>';
    }

    $serviceFee = $totalOrder * $vendor['serviceFeePercent'] / 100;
    if ($serviceFee > $vendor['serviceFeeAmount']) $serviceFee = $vendor['serviceFeeAmount'];
    $total = $totalOrder + $serviceFee;

?>
<div id="wrapper">
    <div id="content">
        <div class="container" id="shopping-cart">
            <div class="container" id="page-wrapper">
                <div class="row">
                    <div class="col-md-12">
                        <div id="area-container">
                            <div class="page-container">
                                <div class="heading pay-header">
                                    <div class="amount"><?php echo number_format($total, 2, ',', '.'); ?> EUR</div>
                                    <div class="info">
                                        <b>bestelling</b>
                                    </div>
                                </div>
                                <div class="bar bar2">
                                    <div class="language">
                                        <a href="#">
                                            <span class="selectedLanguage">NL</span>
                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                        </a>
                                        <div class="menu hidden">
                                            <ul>
                                                <li class="selected">NL</li>
                                                <!-- <li>EN</li>
                                                <li>FR</li> -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-details" style="background-color: white">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th data-trans="" data-trn-key="Productnaam">Productnaam
                                            </th>
                                            <th data-trans="" data-trn-key="Totaal">Totaal</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $tableRows; ?>
                                            <tr>
                                                <td style="text-align:left">
                                                    <p>Bestellingen</p>
                                                    <p>Service</p>
                                                    <p>TOTAAL</p>
                                                </td>
                                                <td>
                                                    <p><?php echo number_format($totalOrder, 2, ',', '.'); ?> &euro;</p>
                                                    <p><?php echo number_format($serviceFee, 2, ',', '.'); ?> &euro;</p>
                                                    <p><?php echo number_format($total, 2, ',', '.'); ?> &euro;</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="bar">
                                    <div class="bar-title">
                                        <span data-trans="" data-trn-key="Kies een betaalmethode">
                                            Kies een betaalmethode
                                        </span>
                                    </div>
                                    <span class="bar-title-original hidden">
                                        <span data-trans="" data-trn-key="Kies een betaalmethode">Kies een betaalmethode</span>
                                    </span>
                                </div>
                                <div class="content-container clearfix" id="paymentMethodsContainer">
                                    <div class="payment-container methods">
                                        <?php if ($vendor['ideal'] === '1') { ?>
                                            <a
                                                href="javascript:void(0)"
                                                class="paymentMethod method-ideal"
                                                onclick="toogleElements('idealBanks', 'paymentMethodsContainer', 'hidden')">
                                                <img src="https://tiqs.com/shop/assets/imgs/extra/ideal.png" alt="iDEAL">
                                                <span>iDEAL</span>
                                            </a>
                                        <?php } ?>
                                        <?php if ($vendor['creditCard'] === '1') { ?>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $creditCardPaymentType; ?>/0" class="paymentMethod method-card" >
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/creditcard.png" alt="Creditcard">
                                                <span data-trans="" data-trn-key="Creditcard">Creditcard</span>
                                            </a>
                                        <?php } ?>
                                        <?php if ($vendor['bancontact'] === '1') { ?>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $bancontactPaymentType; ?>/0" class="paymentMethod method-card" >
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/bancontact.png" alt="bancontact">
                                                <span data-trans="" data-trn-key="Bancontact">Bancontact</span>
                                            </a>
                                        <?php } ?>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <?php if ($vendor['ideal'] === '1') { ?>
                                    <div class="method method-ideal hidden"  id="idealBanks">
                                        <div class="title hidden"><span data-trans="" data-trn-key="Kies een bank">Kies een bank</span>
                                        </div>                                        
                                        <div class="payment-container">
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/1" class="bank paymentMethod abn_amro">
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/abn_amro.png" alt="ABN AMRO">
                                                <span>ABN AMRO</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/8" class="bank paymentMethod asn_bank">
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/asn_bank.png" alt="ASN Bank">
                                                <span>ASN Bank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/5080" class="bank paymentMethod bunq">
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra//bunq.png" alt="Bunq">
                                                <span>Bunq</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/5082" class="bank paymentMethod handelsbanken">
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/handelsbanken.png" alt="Handelsbanken">
                                                <span>Handelsbanken</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/4" class="bank paymentMethod ing">
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/ing.png" alt="ING">
                                                <span>ING</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/12" class="bank paymentMethod knab">
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/knab(1).png" alt="Knab">
                                                <span>Knab</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/5081" class="bank paymentMethod moneyou">
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/moneyou.png" alt="Moneyou">
                                                <span>Moneyou</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/2" class="bank paymentMethod rabobank">
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/rabobank.png" alt="Rabobank">
                                                <span>Rabobank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/9" class="bank paymentMethod regiobank">
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/regiobank.png" alt="RegioBank">
                                                <span>RegioBank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/5" class="bank paymentMethod sns_bank">
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/sns_bank.png" alt="SNS Bank">
                                                <span>SNS Bank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/10" class="bank paymentMethod triodos_bank">
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/triodos_bank.png" alt="Triodos Bank">
                                                <span>Triodos Bank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/11" class="bank paymentMethod van_lanschot">
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/van_lanschot.png" alt="van Lanschot">
                                                <span>van Lanschot</span>
                                            </a>
                                            <div class="clearfix"></div>
                                            <a
                                                href="javascript:void(0)"                                                
                                                onclick="toogleElements('paymentMethodsContainer', 'idealBanks', 'hidden')"
                                                >
                                                Back to payment methods
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="footer" style="text-align:left">
                                    <a href="<?php echo base_url(); ?>checkout_order" class="btn-cancel">
                                        <i class="fa fa-arrow-left"></i>
                                        <span data-trans="" data-trn-key="Annuleren">Annuleren</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($vendor['ideal'] === '1') { ?>
<script>

    function toogleElements(showId, hideId, className) {
        document.getElementById(showId).classList.toggle(className)
        document.getElementById(hideId).classList.toggle(className)
    }
</script>
<?php } ?>