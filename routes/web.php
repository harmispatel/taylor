<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\DefaultMeasurerCommissionController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\DigitalProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PurchaseHistoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Seller\SellerRequestController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\WalletController;

use App\Http\Controllers\Payment\AamarpayController;
use App\Http\Controllers\Payment\AuthorizenetController;
use App\Http\Controllers\Payment\BkashController;
use App\Http\Controllers\Payment\InstamojoController;
use App\Http\Controllers\Payment\MercadopagoController;
use App\Http\Controllers\Payment\NgeniusController;
use App\Http\Controllers\Payment\PayhereController;
use App\Http\Controllers\Payment\PaypalController;
use App\Http\Controllers\Payment\PaystackController;
use App\Http\Controllers\Payment\SslcommerzController;
use App\Http\Controllers\Payment\RazorpayController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\Payment\VoguepayController;
use App\Http\Controllers\Payment\IyzicoController;
use App\Http\Controllers\Payment\NagadController;
use App\Http\Controllers\Payment\PaykuController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\MeasurerController;
use App\Http\Controllers\ProductForumController;
use App\Http\Controllers\{RequestController,ModelAlbumsController,ModelLikeController,ModelCommentController,RepairStoreController,RepairerWithdrawRequestController};

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::controller(DemoController::class)->group(function () {
    Route::get('/demo/cron_1', 'cron_1');
    Route::get('/demo/cron_2', 'cron_2');
    Route::get('/convert_assets', 'convert_assets');
    Route::get('/convert_category', 'convert_category');
    Route::get('/convert_tax', 'convertTaxes');
    Route::get('/insert_product_variant_forcefully', 'insert_product_variant_forcefully');
    Route::get('/update_seller_id_in_orders/{id_min}/{id_max}', 'update_seller_id_in_orders');
    Route::get('/migrate_attribute_values', 'migrate_attribute_values');
});

Route::get('/refresh-csrf', function() {
    return csrf_token();
});

// AIZ Uploader
Route::controller(AizUploadController::class)->group(function () {
    Route::post('/aiz-uploader', 'show_uploader');
    Route::post('/aiz-uploader/upload', 'upload');
    Route::get('/aiz-uploader/get_uploaded_files', 'get_uploaded_files');
    Route::post('/aiz-uploader/get_file_by_ids', 'get_preview_files');
    Route::get('/aiz-uploader/download/{id}', 'attachment_download')->name('download_attachment');
});

Auth::routes(['verify' => true]);

// Login
Route::controller(LoginController::class)->group(function () {
    Route::get('/logout', 'logout');
    Route::get('/social-login/redirect/{provider}', 'redirectToProvider')->name('social.login');
    Route::get('/social-login/{provider}/callback', 'handleProviderCallback')->name('social.callback');
});

Route::controller(SellerRequestController::class)->group(function () {
    Route::get('seller/nearbyMeasurers/customer/{id}', 'nearby_measurers')->name('requests.nearby_measurers_customers');
});

Route::controller(VerificationController::class)->group(function () {
    Route::get('/email/resend', 'resend')->name('verification.resend');
    Route::get('/verification-confirmation/{code}', 'verification_confirmation')->name('email.verification.confirmation');
});
Route::controller(CustomerController::class)->group(function () {

    Route::post('measurer/conversations/create', 'measurer_conversations_create')->name('measurer.conversations.create');
    Route::get('measurer/appointments/create', 'appointment_create')->name('measurer.appointment.create');
    Route::match(['get', 'post'], 'measurer/conversations/show/{id}','measurer_conversations')->name('measurer.conversations');
});

Route::group(['middleware' => ['app_auto_language']], function() {
Route::controller(HomeController::class)->group(function () {
    Route::get('/email_change/callback', 'email_change_callback')->name('email_change.callback');
    Route::post('/password/reset/email/submit', 'reset_password_with_code')->name('password.update');
    Route::get('/users/login', 'login')->name('user.login');
    Route::get('/users/registration', 'registration')->name('user.registration');
    Route::post('/users/login/cart', 'cart_login')->name('cart.login.submit');


    //Home Page

    Route::get('/', 'index')->name('home');

    Route::post('/home/section/featured', 'load_featured_section')->name('home.section.featured');
    Route::post('/home/section/best_selling', 'load_best_selling_section')->name('home.section.best_selling');
    Route::post('/home/section/home_categories', 'load_home_categories_section')->name('home.section.home_categories');
    Route::post('/home/section/best_sellers', 'load_best_sellers_section')->name('home.section.best_sellers');

    //category dropdown menu ajax call
    Route::post('/category/nav-element-list', 'get_category_items')->name('category.elements');

    //Flash Deal Details Page
    Route::get('/flash-deals', 'all_flash_deals')->name('flash-deals');
    Route::get('/flash-deal/{slug}', 'flash_deal_details')->name('flash-deal-details');

    Route::get('/product/{slug}', 'product')->name('product');
    Route::post('/product/variant_price', 'variant_price')->name('products.variant_price');
    Route::get('/shop/{slug}', 'shop')->name('shop.visit');
    Route::get('/shop/{slug}/{type}', 'filter_shop')->name('shop.visit.type');

    Route::get('/customer-packages', 'premium_package_index')->name('customer_packages_list_show');

    Route::get('/brands', 'all_brands')->name('brands.all');
    Route::get('/categories', 'all_categories')->name('categories.all');
    Route::get('/sellers', 'all_seller')->name('sellers');
    Route::get('/coupons', 'all_coupons')->name('coupons.all');
    Route::get('/inhouse', 'inhouse_products')->name('inhouse.all');


    // Policies
    Route::get('/seller-policy', 'sellerpolicy')->name('sellerpolicy');
    Route::get('/return-policy', 'returnpolicy')->name('returnpolicy');
    Route::get('/support-policy', 'supportpolicy')->name('supportpolicy');
    Route::get('/terms', 'terms')->name('terms');
    Route::get('/privacy-policy', 'privacypolicy')->name('privacypolicy');

    Route::get('/track-your-order', 'trackOrder')->name('orders.track');


    // seller as  measurer appointment
    Route::get('seller/appointments', 'measurer_appointments')->name('seller.appointments')->middleware(['seller', 'verified', 'user']);;

    // show new label on sidebar
    Route::get('sidebar/show-newlabel', 'showNewLabelOnSidebar')->name('show.sidebar.newlabel');

    // minutes add work and make a column null
    Route::get('last-login-at-column/make-null', 'makeLastLoginAtColumnNull')->name('makeLastLoginAtColumnNull');

});

});

Route::controller(ModelLikeController::class)->group(function () {
    Route::post('modelLike','store')->name('like.model');
 });
// Language Switch
Route::post('/language', [LanguageController::class, 'changeLanguage'])->name('language.change');

// Currency Switch
Route::post('/currency', [CurrencyController::class, 'changeCurrency'])->name('currency.change');


Route::get('/sitemap.xml', function() {
    return base_path('sitemap.xml');
});

// Classified Product
Route::controller(CustomerProductController::class)->group(function () {
    Route::get('/customer-products', 'customer_products_listing')->name('customer.products');
    Route::get('/customer-products?category={category_slug}', 'search')->name('customer_products.category');
    Route::get('/customer-products?city={city_id}', 'search')->name('customer_products.city');
    Route::get('/customer-products?q={search}', 'search')->name('customer_products.search');
    Route::get('/customer-products/admin', 'initPayment')->name('profile.edit');
    Route::get('/customer-product/{slug}', 'customer_product')->name('customer.product');
});

// Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'index')->name('search');
    Route::get('/search?keyword={search}', 'index')->name('suggestion.search');
    Route::post('/ajax-search', 'ajax_search')->name('search.ajax');
    Route::get('/category/{category_slug}', 'listingByCategory')->name('products.category');
    Route::get('/brand/{brand_slug}', 'listingByBrand')->name('products.brand');
});

// Cart
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart');
    Route::post('/cart/show-cart-modal', 'showCartModal')->name('cart.showCartModal');
    Route::post('/cart/addtocart', 'addToCart')->name('cart.addToCart');
    Route::post('/cart/removeFromCart', 'removeFromCart')->name('cart.removeFromCart');
    Route::post('/cart/updateQuantity', 'updateQuantity')->name('cart.updateQuantity');
});

//Paypal START
Route::controller(PaypalController::class)->group(function () {
    Route::get('/paypal/payment/done', 'getDone')->name('payment.done');
    Route::get('/paypal/payment/cancel', 'getCancel')->name('payment.cancel');
    Route::post('payment/makeRepairOrderPayment', 'makeRepairOrderPayment')->name('payment.makeRepairOrderPayment');
});
//Paypal END

//Mercadopago START
Route::controller(MercadopagoController::class)->group(function () {
    Route::any('/mercadopago/payment/done', 'paymentstatus')->name('mercadopago.done');
    Route::any('/mercadopago/payment/cancel', 'callback')->name('mercadopago.cancel');
});
//Mercadopago

// SSLCOMMERZ Start
Route::controller(SslcommerzController::class)->group(function () {
    Route::get('/sslcommerz/pay', 'index');
    Route::POST('/sslcommerz/success', 'success');
    Route::POST('/sslcommerz/fail', 'fail');
    Route::POST('/sslcommerz/cancel', 'cancel');
    Route::POST('/sslcommerz/ipn', 'ipn');
});
//SSLCOMMERZ END

//Stipe Start
Route::controller(StripeController::class)->group(function () {
    Route::get('stripe', 'stripe');
    Route::post('/stripe/create-checkout-session', 'create_checkout_session')->name('stripe.get_token');
    Route::any('/stripe/payment/callback', 'callback')->name('stripe.callback');
    Route::get('/stripe/success', 'success')->name('stripe.success');
    Route::get('/stripe/cancel', 'cancel')->name('stripe.cancel');
});
//Stripe END

// Compare
Route::controller(CompareController::class)->group(function () {
    Route::get('/compare', 'index')->name('compare');
    Route::get('/compare/reset', 'reset')->name('compare.reset');
    Route::post('/compare/addToCompare', 'addToCompare')->name('compare.addToCompare');
});


// Product Forum
Route::controller(ProductForumController::class)->group(function () {
    Route::post('/add-forum-user', 'add_forum_user')->name('user.forum.add');

});

// Subscribe
Route::resource('subscribers', SubscriberController::class);


Route::get('/show/user/measurment/id/{uuid}',[HomeController::class, 'show_user_measurement'])->name('show_user_measurement');

Route::post('search/measurment', [HomeController::class, 'searchMeasurment'])->name('search.measurment');
Route::group(['middleware' => ['user', 'verified', 'unbanned']], function() {
    Route::controller(MeasurerController::class)->group(function () {
        Route::match(['get', 'post'], 'measurer/conversations/show/{id}','measurer_conversations')->name('user.measurer.conversations');
    });
});
// Model Comment Routes
Route::controller(ModelCommentController::class)->group(function () {
    Route::post('addcomment','store')->name('user.add.comment');
 });

Route::group(['middleware' => ['user', 'verified', 'unbanned']], function() {

    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/new-user-verification', 'new_verify')->name('user.new.verify');
        Route::post('/new-user-email', 'update_email')->name('user.change.email');
        Route::post('/user/update-profile', 'userProfileUpdate')->name('user.profile.update');
        Route::post('/user/bank-details', 'updateBankDetails')->name('update.bank_details');
        Route::get('/appointments', 'appointments')->name('appointments');
        Route::get('/customer-direct-appointments', 'customer_direct_appointments_of_measurments')->name('customer_direct_appointments_of_measurments');
        Route::get('/completed-measures', 'completedMeasures')->name('completed_measures');
        Route::get('/direct-completed-measures', 'completedDirectMeasures')->name('direct_completed_measures');
        Route::get('/measurer/appointments', 'measurer_appointments')->name('measurer-appointments');
        Route::get('/measurer/direct/appointments', 'direct_measurer_appointments')->name('direct-measurer-appointments');
        Route::get('/measurer/appointment/accept-reject/{id}/{status}', 'measurer_appointment_accept_reject')->name('measurer-appointment.accept-reject');
        Route::match(['get', 'post'], '/measurer/measurement/{id}', 'measurer_measurement')->name('measurer-measurement');
        Route::match(['get', 'post'], 'direct/measurer/measurement/{id}', 'direct_measurer_measurement')->name('direct-measurer-measurement');
        Route::get('/mark-as-complete/{id}', 'mark_as_complete')->name('user.mark_as_complete');
        Route::post('/order-create', 'order_create')->name('user.order.create');
        Route::get('customer/appointments/video/{id}', 'appointments_video_customer')->name('appointments_video_customer');
        Route::get('measurer/appointments/video/{id}', 'appointments_video')->name('appointments_video');
        Route::get('measurer/appointments/video/delete/{id}', 'appointments_video_delete')->name('appointments_video_delete');
        Route::post('measurer/appointments/video/post', 'appointments_video_post')->name('appointments_video_post');
        Route::get('measurer/availablity', [HomeController::class, 'measurer_availablity'])->name('measurer.availablity');
        Route::post('measurer/availablity/store', [HomeController::class, 'measurer_availablity_save'])->name('measurer.availablity.save');
        Route::get('model-gallery', [HomeController::class, 'model_gallery'])->name('model_gallery');
        Route::get('delete_model_picture/{id}', [HomeController::class, 'delete_model_picture'])->name('delete_model_picture');
        Route::post('model_upload_image', [HomeController::class, 'model_upload_image'])->name('model_upload_image');
        Route::post('store_model_details', [HomeController::class, 'store_model_details'])->name('store_model_details');
        Route::post('set_model_commission', [HomeController::class, 'set_model_commission'])->name('set_model_commission');
        Route::get('appointment_for_modeling', [HomeController::class, 'appointment_for_modeling'])->name('appointment_for_modeling');
        Route::get('update_request_model_status/{request}/{status}', [HomeController::class, 'update_request_model_status'])->name('update_request_model_status');
        Route::post('temporary_model_commission_store', [HomeController::class, 'temporary_model_commission_store'])->name('temporary_model_commission_store');
        Route::get('reject_measurment/{measurment_id}/{status}', [HomeController::class, 'reject_measurment'])->name('reject_measurment');
        // New routes created on 28-07-2023
        Route::get('model_list','model_list')->name('user.model_list');
        Route::match(['get','post'],'all_model_gallery','all_model_gallery')->name('user.all_model_gallery');
        Route::get('view_model_details/{id}','view_model_details')->name('user.view_model_details');
        Route::get('album_list/{id}','album_list')->name('user.album_list');
        Route::get('single_model_gallery/{id}','single_model_gallery')->name('user.single_model_gallery');
        Route::get('model_conversations_create/{model_id}','model_conversations_create')->name('user.model_conversations_create');
        Route::match(['get', 'post'], 'model_conversations/{conversation_id}/{model_id}','model_conversations')->name('user.model_conversations');
        Route::post('model_appointment_create','model_appointment_create')->name('user.model_appointment_create');
        Route::get('customer_requests_to_be_model','customer_requests_to_be_model')->name('user.requests_to_be_model');
        Route::get('customer/nearbyMeasurers/customer/{id}','nearby_models')->name('user.requests.nearby_models');
        Route::post('model_conversations_create-post/{model_id}','model_conversations_create')->name('user.model_conversations_create_post');
        Route::post('verify-accesscode','verify_access_code')->name('user.verify-accesscode');
        Route::get('album_post_list/{id}', [HomeController::class, 'album_post_list'])->name('user.album_post_list');
        Route::get('album-post-approval', [HomeController::class, 'album_post_approval'])->name('album-post-approval');
        Route::get('post-approval/{post_id}/{approve}', [HomeController::class, 'approve_post'])->name('approve_post');
        Route::get('view_post_detail/{id}', [HomeController::class, 'view_post_detail'])->name('user.view_post_detail');
    });
    Route::controller(ModelAlbumsController::class)->group(function () {

        Route::get('/model-albums', 'index')->name('model-albums');
        Route::post('/store-albums', 'store')->name('store-albums');
        Route::post('/getAlbumDetails', 'getAlbumDetails')->name('getAlbumDetails');
        Route::get('/delete-albums/{id}', 'destroy')->name('delete-albums');
        Route::get('/view-albums/{id}', 'show')->name('view-albums');
    });
    Route::controller(RepairStoreController::class)->group(function () {
        Route::get('repairStore/availablity','index')->name('repairStore.availablity');
        Route::get('repairStore/service','serviceList')->name('repairStore.service');
        Route::get('repairStore/myOrders','myOrders')->name('repairStore.myOrders');
        Route::post('repairStore/service/store','storeService')->name('store.service');
        Route::post('repairStore/availablity/store','store')->name('repairStore.availablity.save');
        Route::post('repairStore/getServiceDetails', 'getServiceDetails')->name('getAlbumDetails');
        Route::post('repairStore/updateOrderStatus', 'updateOrderStatus')->name('updateOrderStatus');
        Route::post('repairStore/acceptOrder', 'acceptOrder')->name('repairStore.acceptOrder');
        Route::get('/delete-service/{id}', 'destroy')->name('delete.service');
    });


    Route::get('/all-notifications', [NotificationController::class, 'index'])->name('all-notifications');

});

Route::group(['middleware' => ['verified', 'unbanned']], function() {

    // Checkout Routs
    Route::group(['prefix' => 'checkout'], function() {
        Route::controller(CheckoutController::class)->group(function () {
            Route::get('/', 'get_shipping_info')->name('checkout.shipping_info');
            Route::any('/delivery_info', 'store_shipping_info')->name('checkout.store_shipping_infostore');
            Route::post('/payment_select', 'store_delivery_info')->name('checkout.store_delivery_info');
            Route::get('/order-confirmed', 'order_confirmed')->name('order_confirmed');
            Route::post('/payment', 'checkout')->name('payment.checkout');
            Route::post('/get_pick_up_points', 'get_pick_up_points')->name('shipping_info.get_pick_up_points');
            Route::get('/payment-select', 'get_payment_info')->name('checkout.payment_info');
            Route::post('/apply_coupon_code', 'apply_coupon_code')->name('checkout.apply_coupon_code');
            Route::post('/remove_coupon_code', 'remove_coupon_code')->name('checkout.remove_coupon_code');
            //Club point
            Route::post('/apply-club-point', 'apply_club_point')->name('checkout.apply_club_point');
            Route::post('/remove-club-point', 'remove_club_point')->name('checkout.remove_club_point');
        });
    });

    // Purchase History
    Route::resource('purchase_history', PurchaseHistoryController::class);
    Route::controller(PurchaseHistoryController::class)->group(function () {
        Route::get('/purchase_history/details/{id}', 'purchase_history_details')->name('purchase_history.details');
        Route::get('/purchase_history/destroy/{id}', 'order_cancel')->name('purchase_history.destroy');
        Route::get('digital_purchase_history', 'digital_index')->name('digital_purchase_history.index');
    });

    // Wishlist
    Route::resource('wishlists', WishlistController::class);
    Route::post('/wishlists/remove', [WishlistController::class, 'remove'])->name('wishlists.remove');

    // Wallet
    Route::controller(WalletController::class)->group(function () {
        Route::get('/wallet', 'index')->name('wallet.index');
        Route::post('/recharge', 'recharge')->name('wallet.recharge');
    });

    // Money Withdraw Requests
    Route::controller(RepairerWithdrawRequestController::class)->group(function () {

        Route::post('repairer/money-withdraw-request/store', 'store')->name('repairer.money_withdraw_request.store');
    });

    // Support Ticket
    Route::resource('support_ticket', SupportTicketController::class);
    Route::post('support_ticket/reply', [SupportTicketController::class, 'seller_store'])->name('support_ticket.seller_store');

    // Customer Package
    Route::post('/customer_packages/purchase',[CustomerPackageController::class, 'purchase_package'])->name('customer_packages.purchase');

    // Customer Product
    Route::resource('customer_products', CustomerProductController::class);
    Route::controller(CustomerProductController::class)->group(function () {
        Route::get('/customer_products/{id}/edit', 'edit')->name('customer_products.edit');
        Route::post('/customer_products/published', 'updatePublished')->name('customer_products.published');
        Route::post('/customer_products/status', 'updateStatus')->name('customer_products.update.status');
        Route::get('/customer_products/destroy/{id}', 'destroy')->name('customer_products.destroy');
    });

    // Product Review
    Route::post('/product_review_modal', [ReviewController::class, 'product_review_modal'])->name('product_review_modal');

    // Digital Product
    Route::controller(DigitalProductController::class)->group(function () {
        Route::get('/digital-products/download/{id}', 'download')->name('digital-products.download');
    });

});

Route::group(['middleware' => ['auth']], function() {

    Route::get('invoice/{order_id}', [InvoiceController::class, 'invoice_download'])->name('invoice.download');

    // Reviews
    Route::resource('/reviews', ReviewController::class);

    // Product Query
    Route::resource('conversations', ConversationController::class);
    Route::controller(ConversationController::class)->group(function () {
        Route::get('/conversations/destroy/{id}', 'destroy')->name('conversations.destroy');
        Route::post('conversations/refresh', 'refresh')->name('conversations.refresh');

        Route::post('/set/commission', 'set_commission')->name('conversations.commission');
    });

    Route::resource('messages', MessageController::class);

    //Address
    Route::resource('addresses', AddressController::class);
    Route::controller(AddressController::class)->group(function () {
        Route::post('/get-states', 'getStates')->name('get-state');
        Route::post('/get-cities', 'getCities')->name('get-city');
        Route::post('/addresses/update/{id}', 'update')->name('addresses.update');
        Route::get('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
        Route::get('/addresses/set_default/{id}', 'set_default')->name('addresses.set_default');
    });
});

Route::resource('shops', ShopController::class);

Route::get('/instamojo/payment/pay-success', [InstamojoController::class, 'success'])->name('instamojo.success');

Route::post('rozer/payment/pay-success', [RazorpayController::class, 'payment'])->name('payment.rozer');

Route::get('/paystack/payment/callback', [PaystackController::class, 'handleGatewayCallback']);

Route::controller(VoguepayController::class)->group(function () {
    Route::get('/vogue-pay', 'showForm');
    Route::get('/vogue-pay/success/{id}', 'paymentSuccess');
    Route::get('/vogue-pay/failure/{id}', 'paymentFailure');
});


//Iyzico
Route::any('/iyzico/payment/callback/{payment_type}/{amount?}/{payment_method?}/{combined_order_id?}/{customer_package_id?}/{seller_package_id?}', [IyzicoController::class, 'callback'])->name('iyzico.callback');

//payhere below
Route::controller(PayhereController::class)->group(function () {
    Route::get('/payhere/checkout/testing', 'checkout_testing')->name('payhere.checkout.testing');
    Route::get('/payhere/wallet/testing', 'wallet_testing')->name('payhere.checkout.testing');
    Route::get('/payhere/customer_package/testing', 'customer_package_testing')->name('payhere.customer_package.testing');

    Route::any('/payhere/checkout/notify', 'checkout_notify')->name('payhere.checkout.notify');
    Route::any('/payhere/checkout/return', 'checkout_return')->name('payhere.checkout.return');
    Route::any('/payhere/checkout/cancel', 'chekout_cancel')->name('payhere.checkout.cancel');

    Route::any('/payhere/wallet/notify', 'wallet_notify')->name('payhere.wallet.notify');
    Route::any('/payhere/wallet/return', 'wallet_return')->name('payhere.wallet.return');
    Route::any('/payhere/wallet/cancel', 'wallet_cancel')->name('payhere.wallet.cancel');

    Route::any('/payhere/seller_package_payment/notify', 'seller_package_notify')->name('payhere.seller_package_payment.notify');
    Route::any('/payhere/seller_package_payment/return', 'seller_package_payment_return')->name('payhere.seller_package_payment.return');
    Route::any('/payhere/seller_package_payment/cancel', 'seller_package_payment_cancel')->name('payhere.seller_package_payment.cancel');

    Route::any('/payhere/customer_package_payment/notify', 'customer_package_notify')->name('payhere.customer_package_payment.notify');
    Route::any('/payhere/customer_package_payment/return', 'customer_package_return')->name('payhere.customer_package_payment.return');
    Route::any('/payhere/customer_package_payment/cancel', 'customer_package_cancel')->name('payhere.customer_package_payment.cancel');
});


//N-genius
Route::controller(NgeniusController::class)->group(function () {
    Route::any('ngenius/cart_payment_callback', 'cart_payment_callback')->name('ngenius.cart_payment_callback');
    Route::any('ngenius/wallet_payment_callback', 'wallet_payment_callback')->name('ngenius.wallet_payment_callback');
    Route::any('ngenius/customer_package_payment_callback', 'customer_package_payment_callback')->name('ngenius.customer_package_payment_callback');
    Route::any('ngenius/seller_package_payment_callback', 'seller_package_payment_callback')->name('ngenius.seller_package_payment_callback');
});

//bKash
Route::controller(BkashController::class)->group(function () {
    Route::post('/bkash/createpayment', 'checkout')->name('bkash.checkout');
    Route::post('/bkash/executepayment', 'excecute')->name('bkash.excecute');
    Route::get('/bkash/success', 'success')->name('bkash.success');
});

//Nagad
Route::get('/nagad/callback', [NagadController::class, 'verify'])->name('nagad.callback');

//aamarpay
Route::controller(AamarpayController::class)->group(function () {
    Route::post('/aamarpay/success','success')->name('aamarpay.success');
    Route::post('/aamarpay/fail','fail')->name('aamarpay.fail');
});

//Authorize-Net-Payment
Route::post('/dopay/online', [AuthorizenetController::class, 'handleonlinepay'])->name('dopay.online');

//payku
Route::get('/payku/callback/{id}', [PaykuController::class, 'callback'])->name('payku.result');

//Blog Section
Route::controller(BlogController::class)->group(function () {
    Route::get('/blog', 'all_blog')->name('blog');
    Route::get('/blog/{slug}', 'blog_details')->name('blog.details');
});

Route::controller(PageController::class)->group(function () {
    //mobile app balnk page for webview
    Route::get('/mobile-page/{slug}', 'mobile_custom_page')->name('mobile.custom-pages');

    //Custom page
    Route::get('/{slug}', 'show_custom_page')->name('custom-pages.show_custom_page');
});

Route::controller(RequestController::class)->group(function () {

    Route::post('/add-request-to-personaliser', 'add_request_to_personaliser')->name('request.add_request_to_personaliser');
});



Route::controller(DefaultMeasurerCommissionController::class)->group(function () {
    Route::post('/measurer/set-default-commission', 'store')->name('setDefaultCommission');
    Route::post('/measurer/measurerCommission/store-temporary', 'storeTempCommission')->name('temporaryCommission.store');
});


