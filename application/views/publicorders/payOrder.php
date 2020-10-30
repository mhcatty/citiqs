<div id="wrapper">
    <div id="content">
        <div class="container" id="shopping-cart">
            <div class="container" id="page-wrapper">
                <div class="row">
                    <div class="col-md-12">
                        <div id="area-container">
                            <div class="page-container">
                                <div class="heading pay-header">
<!--                                    <div class="amount">--><?php //echo number_format($total, 2, ',', '.'); ?><!-- EUR</div>-->
<!--                                    <div class="info">-->
<!--                                        <b>bestelling</b>-->
<!--                                    </div>-->
                                </div>
                                <div class="bar bar2">
<!--                                    <div class="language">-->
<!--                                        <a href="#">-->
<!--                                            <span class="selectedLanguage">NL</span>-->
<!--                                            <i class="fa fa-angle-down" aria-hidden="true"></i>-->
<!--                                        </a>-->
<!--                                        <div class="menu hidden">-->
<!--                                            <ul>-->
<!--                                                <li class="selected">NL</li>-->
<!--                                              <li>EN</li>-->
<!--                                                <li>FR</li> -->
<!--                                            </ul>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                </div>
                                <div class="order-details" style="background-color: white; display:none">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th data-trans="" data-trn-key="Productnaam"><?php echo $this->language->line("PAYMENT-010",'Productname');?>
                                            </th>
                                            <th data-trans="" data-trn-key="Totaal"><?php echo $this->language->line("PAYMENT-020",'Total');?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align:left">
                                                    <p>Bestellingen</p>
                                                    <p>Service</p>
                                                    <p>TOTAAL</p>
                                                    <?php if ($waiterTip) { ?>
                                                    <p>Waiter tip</p>
                                                    <p>TOTAL WITH TIP</p>
                                                    <?php } ?>                                                    
                                                    <p class="voucher" style="display:none">Voucher amount</p>
                                                    <p class="voucher" style="display:none">Pay with other method</p>
                                                </td>
                                                <td>
                                                    <p><?php echo number_format($amount, 2, ',', '.'); ?> &euro;</p>
                                                    <p><?php echo number_format($serviceFee, 2, ',', '.'); ?> &euro;</p>
                                                    <p><?php echo number_format(($amount + $serviceFee), 2, ',', '.'); ?> &euro;</p>
                                                    <?php if ($waiterTip) { ?>
                                                        <p><?php echo number_format($waiterTip, 2, ',', '.'); ?> &euro;</p>
                                                        <p><?php echo number_format(($amount + $serviceFee + $waiterTip), 2, ',', '.'); ?> &euro;</p>
                                                    <?php } ?>
                                                    <p class="voucher" style="display:none"><span id="voucherAmount"></span> &euro;</p>
                                                    <p class="voucher" style="display:none"><span id="leftAmount"></span> &euro;</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="bar">
                                    <div class="bar-title">
                                        <span data-trans="" data-trn-key="Kies een betaalmethode">
												<?php echo $this->language->line("PAYMENT-050",'Kies een betaalmethode');?>
                                        </span>
                                    </div>
                                    <span class="bar-title-original hidden">
                                        <span data-trans="" data-trn-key="Kies een betaalmethode"><?php echo $this->language->line("PAYMENT-050",'Kies een betaalmethode');?></span>
                                    </span>
                                </div>
                                <div class="content-container clearfix" id="paymentMethodsContainer">
                                    <div class="payment-container methods">
                                        <?php if ($vendor['ideal'] === '1') { ?>
                                            <a href="javascript:void(0)" onclick="toogleElements('idealBanks', 'paymentMethodsContainer', 'hidden')" class="paymentMethod method-card" >
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/ideal.png" alt="iDEAL">
                                                <span>iDEAL</span>
                                            </a>
                                        <?php } ?>
                                        <?php if ($vendor['creditCard'] === '1') { ?>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $creditCardPaymentType; ?>/0<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="paymentMethod method-card addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/creditcard.png" alt="Creditcard">
                                                <span>Creditcard</span>
                                            </a>
                                        <?php } ?>
										<?php if ($vendor['payconiq'] === '1') { ?>
											<a href="<?php echo base_url(); ?>insertorder/<?php echo $payconiqPaymentType; ?>/0<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="paymentMethod method-card addTargetBlank">
												<img src="https://tiqs.com/alfred/assets/home/imgs/extra/payconiq.png" alt="Payconiq">
												<span>Payconiq</span>
											</a>
										<?php } ?>
                                        <?php if ($vendor['bancontact'] === '1') { ?>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $bancontactPaymentType; ?>/0<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="paymentMethod method-card addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/bancontact.png" alt="bancontact">
                                                <span>Bancontact</span>
                                            </a>
                                        <?php } ?>
                                        <?php if ($vendor['giro'] === '1') { ?>
                                            <a href="javascript:void(0)" onclick="toogleElements('giroBanks', 'paymentMethodsContainer', 'hidden')" class="paymentMethod method-card" >
                                                <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/giropay(1).png" alt="bancontact">
                                                <span data-trans="" data-trn-key="Bancontact">Giropay</span>
                                            </a>
                                        <?php } ?>
                                        <?php if ($localType === intval($spot['spotTypeId'])) { ?>
                                            <?php if ($vendor['prePaid'] === '1') { ?>
                                                <p class="paymentMethod method-card" data-toggle="modal" data-target="#prePaid">
                                                    <img src="<?php echo base_url() . 'assets/images/waiter.png'; ?>" alt="Pay at waiter" />
                                                    <?php if ($vendor['vendorId'] == THGROUP) { ?>
                                                        <span>Collect at the bar</span>
                                                    <?php } else { ?>
                                                        <span>Pay at waiter</span>
                                                    <?php } ?>
                                                </p>
                                            <?php } ?>
                                            <?php if ($vendor['postPaid'] === '1') { ?>
                                                <p class="paymentMethod method-card" data-toggle="modal" data-target="#postPaid">
                                                    <img src="<?php echo base_url() . 'assets/images/waiter.png'; ?>" alt="Pay at waiter" />
                                                    
                                                    <?php if ($vendor['vendorId'] == THGROUP) { ?>
                                                        <span>Collect at the bar</span>
                                                    <?php } else { ?>
                                                        <span>Pay at waiter</span>
                                                    <?php } ?>
                                                </p>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if ($vendor['pinMachine'] === '1') { ?>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $pinMachinePaymentType; ?>/TH-9268-3020<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="paymentMethod method-card addTargetBlank">
                                            <img src="<?php echo base_url() . 'assets/home/images/pinmachine.png'; ?>" alt="pin machine">
                                                <span>Pin machine</span>
                                            </a>
                                        <?php } ?>
                                        <?php if ($vendor['vaucher'] === '1') { ?>
                                            <p data-toggle="modal" data-target="#voucher" class="paymentMethod method-card" >
                                                <img src="<?php echo base_url() . 'assets/home/images/voucher.png'; ?>" alt="voucher" >
                                                <span>Voucher</span>
                                            </p>
                                        <?php } ?>
                                        <div class="clearfix"></div>
                                    </div>

                                </div>

                                <?php if ($vendor['ideal'] === '1') { ?>
                                    <div class="method method-ideal hidden"  id="idealBanks">
                                        <div class="title hidden"><span data-trans="" data-trn-key="Kies een bank"><?php echo $this->language->line("PAYMENT-030",'Choose your bank');?></span>
                                        </div>                                        
                                        <div class="payment-container">
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/1<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod abn_amro addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/abn_amro.png" alt="ABN AMRO">
                                                <span>ABN AMRO</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/8<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod asn_bank addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/asn_bank.png" alt="ASN Bank">
                                                <span>ASN Bank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/5080<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod bunq addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/bunq.png" alt="Bunq">
                                                <span>Bunq</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/5082<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod handelsbanken addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/handelsbanken.png" alt="Handelsbanken">
                                                <span>Handelsbanken</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/4<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod ing addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/ing.png" alt="ING">
                                                <span>ING</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/12<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod knab addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/knab(1).png" alt="Knab">
                                                <span>Knab</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/5081<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod moneyou addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/moneyou.png" alt="Moneyou">
                                                <span>Moneyou</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/2<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod rabobank addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/rabobank.png" alt="Rabobank">
                                                <span>Rabobank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/9<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod regiobank addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/regiobank.png" alt="RegioBank">
                                                <span>RegioBank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/5<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod sns_bank addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/sns_bank.png" alt="SNS Bank">
                                                <span>SNS Bank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/10<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod triodos_bank addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/triodos_bank.png" alt="Triodos Bank">
                                                <span>Triodos Bank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $idealPaymentType; ?>/11<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod van_lanschot addTargetBlank">
                                                <img src="https://tiqs.com/alfred/assets/home/imgs/extra/van_lanschot.png" alt="van Lanschot">
                                                <span>van Lanschot</span>
                                            </a>
                                            <div class="clearfix"></div>
                                            <a
                                                href="javascript:void(0)"                                                
                                                onclick="toogleElements('paymentMethodsContainer', 'idealBanks', 'hidden')"
                                                >
												<?php echo $this->language->line("PAYMENT-910",'Back to payment methods');?>

                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ($vendor['giro'] === '1') { ?>
                                    <div class="method method-ideal hidden"  id="giroBanks">
                                        <div class="title hidden"><span data-trans="" data-trn-key="Kies een bank"><?php echo $this->language->line("PAYMENT-030",'Choose your bank');?></span>
                                        </div>
                                        <div class="payment-container">
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $giroPaymentType; ?>/0<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod addTargetBlank">
                                                <!-- <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/abn_amro.png" alt="ABN AMRO"> -->
                                                <span>Sparkasse</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $giroPaymentType; ?>/0<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod addTargetBlank">
                                                <!-- <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/abn_amro.png" alt="ABN AMRO"> -->
                                                <span>Volksbanken Raiffeisenbanken</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $giroPaymentType; ?>/0<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod addTargetBlank">
                                                <!-- <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/abn_amro.png" alt="ABN AMRO"> -->
                                                <span>Postbank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $giroPaymentType; ?>/0<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod addTargetBlank">
                                                <!-- <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/abn_amro.png" alt="ABN AMRO"> -->
                                                <span>Comdirect</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $giroPaymentType; ?>/0<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod addTargetBlank">
                                                <!-- <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/abn_amro.png" alt="ABN AMRO"> -->
                                                <span>BB Bank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $giroPaymentType; ?>/0<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod addTargetBlank">
                                                <!-- <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/abn_amro.png" alt="ABN AMRO"> -->
                                                <span>MLP Bank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $giroPaymentType; ?>/0<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod addTargetBlank">
                                                <!-- <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/abn_amro.png" alt="ABN AMRO"> -->
                                                <span>PSD Bank</span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>insertorder/<?php echo $giroPaymentType; ?>/0<?php echo '?' . $orderDataGetKey . '=' . $orderRandomKey; ?>" class="bank paymentMethod addTargetBlank">
                                                <!-- <img src="https://tiqs.com/qrzvafood/assets/imgs/extra/abn_amro.png" alt="ABN AMRO"> -->
                                                <span>Deutsche Kreditbank AG</span>
                                            </a>
                                            <div class="clearfix"></div>
                                            <a
                                                href="javascript:void(0)"
                                                onclick="toogleElements('paymentMethodsContainer', 'giroBanks', 'hidden')"
                                                >
												<?php echo $this->language->line("PAYMENT-910",'Back to payment methods');?>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>

                                <!-- modals -->
                                <?php if ($localType === intval($spot['spotTypeId'])) { ?>
                                    <?php if ($vendor['prePaid'] === '1') { ?>
                                        <!-- Modal -->
                                        <div id="prePaid" class="modal" role="dialog">
                                        <div class="modal-dialog modal-sm">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <button
                                                            class="btn btn-success btn-lg"
                                                            style="border-radius:50%; margin-right:5%; font-size:24px"
                                                            <?php
                                                                $cashRedirect  = base_url() . 'cashPayment/' . $this->config->item('orderNotPaid');
                                                                $cashRedirect .= '/' . $this->config->item('prePaid');
                                                                $cashRedirect .= '?' . $orderDataGetKey . '=' . $orderRandomKey;
                                                            ?>
                                                            onclick="redirect('<?php echo $cashRedirect; ?>')"
                                                            >
                                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                        </button>
                                                        <button
                                                            class="btn btn-danger btn-lg"
                                                            style="border-radius:50%; margin-left:5%; font-size:24px"
                                                            data-dismiss="modal"
                                                            >
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if ($vendor['postPaid'] === '1') { ?>
                                        <!-- Modal -->
                                        <div id="postPaid" class="modal" role="dialog">
                                            <div class="modal-dialog modal-sm">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <button
                                                            class="btn btn-success btn-lg"
                                                            style="border-radius:50%; margin-right:5%; font-size:24px"
                                                            <?php
                                                                $cashRedirect  = base_url() . 'cashPayment/' . $this->config->item('orderPaid');
                                                                $cashRedirect .= '/' . $this->config->item('orderPaid');
                                                                $cashRedirect .= '?' . $orderDataGetKey . '=' . $orderRandomKey;
                                                            ?>
                                                            onclick="redirect('<?php echo $cashRedirect; ?>')"
                                                            >
                                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                        </button>
                                                        <button
                                                            class="btn btn-danger btn-lg"
                                                            style="border-radius:50%; margin-left:5%; font-size:24px"
                                                            data-dismiss="modal"
                                                            >
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($vendor['vaucher'] === '1') { ?>
                                    <!-- Modal -->
                                    <div id="voucher" class="modal" role="dialog">
                                        <div class="modal-dialog modal-sm">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <label for="codeId">Insert code from voucher</label>
                                                    <input
                                                        type="text"
                                                        id="codeId"
                                                        class="form-control"
                                                        data-total="<?php echo round($amount, 2); ?>"
                                                        data-total-amount="<?php echo round(($amount + $serviceFee), 2); ?>"
                                                        data-waiter-tip="<?php echo round(($amount + $serviceFee + $waiterTip), 2); ?>"
                                                    />
                                                    <br/>
                                                    <button
                                                        class="btn btn-success btn-lg"
                                                        style="border-radius:50%; margin:30px 5% 0px 0px; font-size:24px"
                                                        onclick="voucherPay('codeId')"
                                                    >
                                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                    </button>
                                                    <button
                                                        class="btn btn-danger btn-lg closeModal"
                                                        style="border-radius:50%; margin:30px 5% 0px 0px; font-size:24px"
                                                        data-dismiss="modal"
                                                    >
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="footer" style="text-align:left">
                                    <a href="<?php echo base_url() . $redirect; ?>" class="btn-cancel">
                                        <i class="fa fa-arrow-left"></i>
                                        <span data-trans="" data-trn-key="Annuleren">BACK</span>
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
