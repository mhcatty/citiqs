<main style="margin:30px 0px">
	<div class="container">
		<div class="col-lg-4 col-12 form-inline">
			<label for='selectSpot'>Select POS spot:&nbsp;&nbsp;</label>
			<select onchange="redirectToNewLocation(this.value)"class="form-control">
				<option value="">Select</option>
				<?php foreach ($spots as $spot) { ?>
					<?php if ($spot['spotActive'] !== '1') continue; ?>
					<option
						value="pos?spotid=<?php echo $spot['spotId']; ?>"
						<?php if ($spot['spotId'] === $spotId) echo 'selected'; ?>
					>
						<?php echo $spot['spotName']; ?>
					</option>
				<?php } ?>
			</select>
		</div>
	</div>
	<?php if (isset($mainProducts)) { ?>
		<div class='pos-template'>
			<div class="container container-large pos-container">
				<div class="row d-flex">
					<div class="col-lg-8">
						<div class="pos_categories" style="margin-bottom: 10px">
							<div class="pos_categories__grid">
								<?php
									foreach ($categories as $index => $category) {
										$categoryId = str_replace(['\'', '"', ' ', '*', '/'], '', $category)
									?>
										<div
											class="pos_categories__single-item <?php if (array_key_first($categories) === $index) echo 'pos_categories__single-item--active'; ?>"
											onclick="showCategory(this, '<?php echo $categoryId; ?>', 'categories')"
											data-id="<?php echo $categoryId; ?>"
										>
											<p>
												<?php echo  $category; ?>
											</p>
										</div>
									<?php
									}
								?>
							</div>
						</div>
						<!-- end categories -->
						<div class="pos-main">
							<div class="pos-main__top-bar">
								<div class="pos-main__add-item">
									<a href="<?php echo base_url() . 'orders'; ?>">
										<button class='pos-main__add-new-button'>
											<i class="fa fa-hand-o-left" aria-hidden="true"></i>
											BACK
										</button>
									</a>
								</div>
								<!-- <div class="pos-main__search">
									<input type="text" class='form-control'>
									<button class="pos-main__search__button"><i class="fas fa-search"></i></button>
								</div> -->
							</div>
							<!-- end pos top bar -->

							<div class="pos-main__grid-content">
								<?php
									foreach ($mainProducts as $category => $products) {
										$categoryId = str_replace(['\'', '"', ' ', '*', '/'], '', $category)
									?>
										<div
											class="pos-main__overflow categories"
											id="<?php echo $categoryId; ?>"
											<?php if (array_key_first($mainProducts) !== $category) echo 'style="display:none"'; ?>
										>
											<div class="pos-main__grid">
												<?php foreach ($products as $product) { ?>
													<?php $productDetails = reset($product['productDetails']); ?>
													<div
														class="pos-item"
														<?php #if ($product['addons'] || $productDetails['addRemark'] === '1') { ?>
                                                            data-toggle="modal" data-target="#single-item-details-modal<?php echo $product['productId']; ?>"
                                                        <?php #} else { ?>
                                                            
                                                        <?php #} ?>
													>
														<?php if ($vendor['showProductsImages'] === '1') { ?>
															<div class='pos-item__image'>
																<img
																	<?php if ($product['productImage'] && file_exists($uploadProductImageFolder . $product['productImage'])) { ?>
																		src="<?php echo base_url() . 'assets/images/productImages/' . $product['productImage']; ?>"
																	<?php } else { ?>
																		src="<?php echo base_url() . 'assets/images/defaultProductsImages/' . $vendor['defaultProductsImage']; ?>"
																	<?php } ?>
																	alt="<?php echo $productDetails['name']; ?>"
																/>
															</div>
														<?php } ?>
														<p class='pos-item__title'><?php echo $productDetails['name']; ?></p>
														<p class='pos-item__price'><?php echo $productDetails['price']; ?>&nbsp;&euro;</p>
													</div>
													<!-- end single pos item-->
												<?php } ?>
											</div>
										</div>
									<?php
									}
								?>
							</div>
						</div>
						<!-- end pos main-->
						<div class="pos_categories__footer">
							<a href="#" class='pos_categories__button pos_categories__button--secondary'>Cancel Order</a>
							<a href="#" class='pos_categories__button pos_categories__button--primary'>Hold Order</a>
						</div>
						<!-- end pos footer -->
					</div>
					<div class="col-lg-4">
						<div class="pos-sidebar">
							<div class="pos-checkout">
								<div class="pos-checkout__header">
									<h3>Checkout</h3>
									<div class="pos-checkout-row pos-checkout-row--top">
										<div class="pos-checkout-delete">
										</div>
										<div class="pos-checkout-name">
											<span>Name</span>
										</div>
										<div class="pos-checkout-quantity">
											<span>QTY</span>
										</div>
										<div class="pos-checkout-price">
											<span>Price</span>
										</div>
									</div>
									<!-- end checkout row -->
								</div>
								<!-- end checout list -->
							</div>
						</div>
						<a href="#" class='pos-checkout__button' onclick="checkout(1)">Pay <span>(10.00$)</span></a>
					</div>
					<!-- end pos sidebar -->
				</div>
				<!-- end row item grid -->
			</div>
		</div>
	<?php } ?>
</main>
<?php if (isset($mainProducts)) { ?>
	<div style="display:none">
		<?php include_once FCPATH . 'application/views/publicorders/includes/modals/makeOrderPos/checkoutModals.php'; ?>
	</div>

	<?php include_once FCPATH . 'application/views/publicorders/includes/modals/makeOrderPos/productModals.php'; ?>
<?php } ?>
<?php include_once FCPATH . 'application/views/publicorders/includes/makeOrderGlobalsJs.php'; ?>
