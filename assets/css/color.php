<?php
header ("Content-Type:text/css");
$color = "#f0f"; // Change your Color Here

function checkhexcolor($color) {
	return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if( isset( $_GET[ 'color' ] ) AND $_GET[ 'color' ] != '' ) {
	$color = "#" . $_GET[ 'color' ];
}

if( !$color OR !checkhexcolor( $color ) ) {
	$color = "#f0f";
}

?>


/*Collect Color Code For Theme Swither*/
.main-menu,
.main-menu li ul,
.about-community,
.service-wrapper.active,
.service-wrapper:hover,
.price-table-header,
.boxed-btn,
.asRange .asRange-selected,
.asRange .asRange-pointer:before,
.asRange .asRange-pointer:after,
.discunt-text,
.happy-clients-box,
.single-investor-wrapper.color-onvestor,
.payment-method,
.scroll-to-top i,
.scroll-to-top i:hover,
.deposit-title,
.slider-activation .owl-nav div:hover,
.slider-activation .owl-dots div.active,
.social-link i:hover,
.panel-default>.panel-heading a[aria-expanded="true"],
.panel-default>.panel-heading,
.single-shape-box.color-onvestor,
input[type="submit"],
.preloader,
button.submit-btn:hover,
button.submit-btn,
.main-login.main-center img,
.slicknav_menu,
.member-hover a:hover,
.single-team-member:hover .member-name,
.head-slider .owl-dots div.active,
.head-slider .owl-nav div:hover

{
background: <?php echo $color;?>;
}
.support-info i,
.header-section h1 span,
.our-plan .section-title h2,
.color-text,
.contact-details i,
.top-investor .color-text,
.section-title .color-text,
.testimoanial-top-text p,
table.table.main-table
     tr:nth-child(1),
.slider-activation .owl-nav div,
.contact-icon,
p.captcha-code,
.single-team-member:hover .member-name h4,
.head-slider .owl-nav div  
{
    color: <?php echo $color;?>;
}
.about-community:after,
.price-table-header:after,
.color-onvestor.single-shape-box:after
{
    border-top-color: <?php echo $color;?>;
}
.happy-clients-icon:before,
.happy-clients-icon:after,
.color-onvestor.single-shape-box:before {
    border-bottom-color: <?php echo $color;?>;
}
.profile-pic img,
input[type="text"],
input[type="email"],
textarea,
button.submit-btn:hover,
button.submit-btn{
    border-color: <?php echo $color;?>;
}
.slider-activation:before{
    box-shadow: 1px -8px 3px -2px <?php echo $color;?>;
}
.slider-activation:after{
    box-shadow: 1px 8px 3px -2px <?php echo $color;?>;
}
.single-team-member:hover{
    border:1px solid <?php echo $color;?>;
}
.single-team-member:hover .member-name h4 {
    color: #fff;
}

.panel-primary{
    border-color: <?php echo $color;?>;
}

.panel-primary > .panel-heading  {
    color: #fff;
    background-color: <?php echo $color;?>; 
    border-color: <?php echo $color;?>; 
}

.panel-primary > .panel-heading h3{
    color: #fff;
    text-transform: uppercase;
}

.btn-primary  {
    background: <?php echo $color;?>;
    border-color: <?php echo $color;?>;
}
<!--.btn-primary:hover{-->
<!--    background: --><?php //echo $color;?><!--;-->
<!--    border-color: --><?php //echo $color;?><!--;-->
<!--}-->

