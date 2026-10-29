<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" type="image/x-icon" href="{{ url('public/img/new-logo_G.jpg') }}">
    <title>Admin</title>
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap1.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/themefy_icon/themify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/niceselect/css/nice-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/owl_carousel/css/owl.carousel.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/gijgo/gijgo.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/font_awesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/tagsinput/tagsinput.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/datepicker/date-picker.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/vectormap-home/vectormap-2.0.2.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/scroll/scrollable.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/datatable/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/datatable/css/responsive.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/datatable/css/buttons.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/text_editor/summernote-bs4.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/morris/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendors/material_icon/material-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/style1.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/css/colors/default.css') }}" id="colorSkinCSS">
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.1.0/uicons-bold-straight/css/uicons-bold-straight.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.0.0/cropper.min.css" integrity="sha256-FeDcPwV8ZgxG1MU5L/b2i8clOmqZJKK0eyDHqrQaXxY=" crossorigin="anonymous" />
</head>
<style>
    #back-top a {
        color: #ffe600;
        background: #000;
    }

    .hide1,
    .hide2 {
        display: none;
    }

    i.fa.fa-trash {
        color: #e70202;
    }

    .form-check .form-check-input {
        float: none;
    }

    tbody,
    td,
    tfoot,
    th,
    thead,
    tr {
        border-color: #0000000d;
        border-style: solid;
        border-width: 0;
    }

    .table>thead {
        vertical-align: bottom;
    }


    div.dt-buttons {
        position: relative;
        float: left;
        margin-bottom: 20px;
    }

    button.dt-button.buttons-excel.buttons-html5 {
        color: #000;
        font-size: 14px;
        border: 1px solid #d3d3d3;
        transition: .6s;
        width: 105px;
        background: transparent;
    }

    button.dt-button.buttons-excel.buttons-html5:hover {
        background: #f3f3f3 !important;
        color: #000000 !important;
        font-size: 14px;
        border: 1px solid #c4c4c4 !important;
    }


    .profileImg {
        display: flex;
        align-items: center;
    }

    .profileImg img {
        border: 1px solid #fff;
        border-radius: 50%;
        margin-right: 16px;
    }

    .profileImg span {
        color: #fff;
        cursor: pointer;
    }

    .profileImg span p {
        color: #ccc;
        line-height: 0;
        font-size: 12px;
        padding-top: 5px;
    }

    .sidebar #sidebar_menu>li.active {
        color: #fff;
    }

    .sidebar #sidebar_menu>li.active a i {
        background: #f6f6f6;
        color: #181c1d;
    }

    .sidebar #sidebar_menu>li.active a {
        background: #f6f6f6;
        color: #181c1d;
    }

    .sidebar #sidebar_menu>li.active>a::after {
        color: #231f20;
        transform: rotate(0deg) translate(0, -50%);
        opacity: 1;
        font-size: 10px;
        right: 15px;
    }

    .sidebar #sidebar_menu>li a:hover {
        background: #f6f6f6 !important;
        color: #181c1d !important;
    }

    .sidebar #sidebar_menu>li a:hover i {
        color: #181c1d
    }

    .collapseshow {
        display: block !important;
    }

    .profileImg p {
        color: #000;
        line-height: 20px;
        font-weight: 700;
        font-family: comfortaa, cursive;
    }

    .profile_author_name .profileImg {
        border-bottom: 1px solid #bebebe;
        padding: 0 10px 15px 10px;
    }

    .active1 {
        background: #f6f6f6;
        color: #181c1d
    }

    .active2 {
        background: transparent;
        color: #181c1d;
        font-weight: 900;
    }

    .active1 i {
        color: #181c1d !important;
    }

    em {
        color: red;
    }

    .sidebar #sidebar_menu>li ul li .active2 {
        font-size: 16px;
        font-weight: 700;
        font-family: sans-serif;
        color: #fac20d;
    }
</style>


<?php
if (!isset($active)) {
    $active = 'dashboard';
}
if (!isset($active1)) {
    $active1 = 'dashboard';
}
//echo $active;
?>

<body class="crm_body_bg">
    <nav class="sidebar vertical-scroll  ps-container ps-theme-default ps-active-y">
        <div class="logo d-flex justify-content-center">
            <a href="{{ url('admin/dashboard') }}"><img src="{{ url('public/img/new-logo_G.jpg') }}"
                    alt="Logo"></a>
            <!-- <h2>Gearz App</h2> -->
            <div class="sidebar_close_icon d-lg-none">
                <i class="ti-close"></i>
            </div>
        </div>
        <ul id="sidebar_menu">
            <li class="mm-active <?php if ($active == 'dashboard') {
                echo 'active';
            } ?>">
                <a href="{{ url('admin/dashboard') }}">
                    <div class="icon_menu">
                        <i class="fa fa-book"></i>
                    </div>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="">
                <a class="has-arrow <?php if ($active == 'master') {
                    echo 'active1';
                } ?>" href="#" aria-expanded="<?php if ($active == 'master') {
                    echo 'true';
                } else {
                    echo 'false';
                } ?>">
                    <div class="icon_menu">
                        <i class="fa fa-asterisk"></i>
                    </div>
                    <span>Master</span>
                </a>
                <ul <?php if ($active == 'master') {
                    echo 'class="collapseshow"';
                } ?>>
                    <li><a class="<?php if ($active1 == 'Category') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/category') }}">Categories</a></li>
                    <li><a class="<?php if ($active1 == 'ServiceType') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/serviceType') }}">Service Types</a></li>
                    <li><a class="<?php if ($active1 == 'Service') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/service') }}">Services</a></li>
                    <li><a class="<?php if ($active1 == 'ServiceIcons') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/serviceIcons') }}">Service Icons</a></li>
                    <li><a class="<?php if ($active1 == 'DealType') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/dealtype') }}">Deal Types</a></li>
                    <li><a class="<?php if ($active1 == 'Notification') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/notification') }}">Notifications</a></li>
                    <li><a class="<?php if ($active1 == 'CarCheck') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/CarCheck') }}">Car Checks</a></li>
                    <li><a class="<?php if ($active1 == 'Staff') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/stafflist') }}">Staff </a></li>
                </ul>

            </li>
            <li class="<?php if ($active == 'user') {
                echo 'active';
            } ?>">
                <a href="{{ url('admin/userList') }}">
                    <div class="icon_menu">
                        <i class="fa fa-users"></i>
                    </div>
                    <span>Users</span>
                </a>
            </li>
            <li class="">
                <a class="has-arrow <?php if ($active == 'banner') {
                    echo 'active1';
                } ?>" href="#" aria-expanded="<?php if ($active == 'banner') {
                    echo 'true';
                } else {
                    echo 'false';
                } ?>">
                    <div class="icon_menu">
                        <i class="fa fa-tag"></i>
                    </div>
                    <span>Banners</span>
                </a>
                <ul <?php if ($active == 'banner') {
                    echo 'class="collapseshow"';
                } ?>>
                    <li><a class="<?php if ($active1 == 'banners') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/banners') }}">Banners</a></li>
                    <li><a class="<?php if ($active1 == 'intro_video') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/intro_video') }}">Intro Video</a>
                    </li>
                    <li><a class="<?php if ($active1 == 'welcome_images') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/welcome_images') }}">Welcome Images</a>
                    </li>
                    <li><a class="<?php if ($active1 == 'image_setting') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/image_setting') }}">Welcome Image
                            Settings</a></li>
                    {{-- <li><a href="{{ url('admin/welcomeimagebackground') }}">Welcome Image Background</a>
      </li> --}}
                </ul>
            </li>
            <li class="<?php if ($active == 'brand') {
                echo 'active';
            } ?>">
                <a href="{{ url('admin/brands') }}">
                    <div class="icon_menu">
                        <i class="fa fa-list"></i>
                    </div>
                    <span>Brands</span>
                </a>
            </li>
            <li class="<?php if ($active == 'pbrand') {
                echo 'active';
            } ?>">
                <a href="{{ url('admin/popularBrands') }}">
                    <div class="icon_menu">
                        <i class="fa fa-fire"></i>
                    </div>
                    <span>Popular Brands</span>
                </a>
            </li>
            <li class="<?php if ($active == 'pcar') {
                echo 'active';
            } ?>">
                <a href="{{ url('admin/popularCar') }}">
                    <div class="icon_menu">
                        <i class="fa fa-car"></i>
                    </div>
                    <span>Popular Cars</span>
                </a>
            </li>
            <li class="">
                <a class="has-arrow <?php if ($active == 'agent') {
                    echo 'active1';
                } ?>" href="#" aria-expanded="<?php if ($active == 'agent') {
                    echo 'true';
                } else {
                    echo 'false';
                } ?>">
                    <div class="icon_menu">
                        <i class="fa fa-user"></i>
                    </div>
                    <span>Agents/Resellers</span>
                </a>
                <ul <?php if ($active == 'agent') {
                    echo 'class="collapseshow"';
                } ?>>
                    <li><a class="<?php if ($active1 == 'agentBanners') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/agentBanners') }}">Banner Images</a></li>
                    <li><a class="<?php if ($active1 == 'agent') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/agent') }}">Agents/Resellers</a></li>
                    <li><a class="<?php if ($active1 == 'popularAgent') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/popularAgent') }}">Popular
                            Agents/Resellers</a></li>
                </ul>
            </li>
            <li class="<?php if ($active == 'deals') {
                echo 'active';
            } ?>">
                <a href="{{ url('admin/dealList') }}" aria-expanded="false">
                    <div class="icon_menu">
                        <i class="fa fa-tag"></i>
                    </div>
                    <span>Deals</span>
                </a>
            </li>
            {{-- <li class>
      <a href="#!" aria-expanded="false">
        <div class="icon_menu">
          <i class="fa fa-users"></i>
        </div>
        <span>Community</span>
      </a>
    </li> --}}
            {{-- <li class="<?php if ($active == 'news') {
                echo 'active';
            } ?>">
      <a href="{{url('admin/news')}}" aria-expanded="false">
        <div class="icon_menu">
          <i class="fa fa-book"></i>
        </div>
        <span>News/Event</span>
      </a>
    </li> --}}
            <li class="">
                <a class="has-arrow <?php if ($active == 'magazine') {
                    echo 'active1';
                } ?>" href="#" aria-expanded="<?php if ($active == 'magazine') {
                    echo 'true';
                } else {
                    echo 'false';
                } ?>">
                    <div class="icon_menu">
                        <i class="fa fa-book"></i>
                    </div>
                    <span>Magazines</span>
                </a>
                <ul <?php if ($active == 'magazine') {
                    echo 'class="collapseshow"';
                } ?>>
                    <li><a class="<?php if ($active1 == 'magazineBanners') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/magazineBanners') }}">Magazine
                            Banners</a></li>
                    <li><a class="<?php if ($active1 == 'magazinemaincategory') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/magazinemaincategory') }}">Magazine
                            Categories</a></li>
                    <li><a class="<?php if ($active1 == 'magazinecategory') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/magazinecategory') }}">Magazine
                            Sub-Categories</a></li>
                    <li><a class="<?php if ($active1 == 'magazinelist') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/magazine') }}">Magazine Lists</a></li>
                </ul>
            </li>
            <li class="">
                <a class="has-arrow <?php if ($active == 'advice') {
                    echo 'active1';
                } ?>" href="#" aria-expanded="<?php if ($active == 'advice') {
                    echo 'true';
                } else {
                    echo 'false';
                } ?>">
                    <div class="icon_menu">
                        <i class="fa fa-key"></i>
                    </div>
                    <span>Encyclopedia</span>
                </a>
                <ul <?php if ($active == 'advice') {
                    echo 'class="collapseshow"';
                } ?>>
                    <li><a class="<?php if ($active1 == 'knowledgeimagesection') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/knowledgeimagesection') }}">Encyclopedia
                            Image Sections</a></li>
                    <li><a class="<?php if ($active1 == 'sponserBanners') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/sponserBanners') }}">Sponser Banners</a>
                    </li>
                    <li><a class="<?php if ($active1 == 'advice') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/advice') }}">Gearz Advices</a></li>
                    <li><a class="<?php if ($active1 == 'translation') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/translation') }}">GearZ Dictionaries</a>
                    </li>
                    <!-- <li><a href="Checkout.html">Checkout</a></li> -->
                </ul>
            </li>
            {{-- service provider --}}
            <li class="">
                <a class="has-arrow <?php if ($active == 'serviceprovider') {
                    echo 'active1';
                } ?>" href="#" aria-expanded="<?php if ($active == 'serviceprovider') {
                    echo 'true';
                } else {
                    echo 'false';
                } ?>">
                    <div class="icon_menu">
                        <i class="fa fa-user"></i>
                    </div>
                    <span>Service Providers</span>
                </a>
                <ul <?php if ($active == 'serviceprovider') {
                    echo 'class="collapseshow"';
                } ?>>
                    <li><a class="<?php if ($active1 == 'serviceprovidercategory') {
                        echo 'active2';
                    } ?>"
                            href="{{ url('admin/serviceprovidercategory') }}">Categories</a></li>
                    <li><a class="<?php if ($active1 == 'serviceproviderservices') {
                        echo 'active2';
                    } ?>"
                            href="{{ url('admin/serviceproviderservices') }}">Services</a></li>
                    <li><a class="<?php if ($active1 == 'serviceprovider') {
                        echo 'active2';
                    } ?>" href="{{ url('admin/serviceProvider') }}">Service
                            Providers</a></li>
                </ul>
            </li>
            {{-- service provider --}}
            {{-- <li class>
      <a href="#!" aria-expanded="false">
        <div class="icon_menu">
          <i class="fa fa-car"></i>
        </div>
        <span>Market</span>
      </a>
    </li>
    <li class>
      <a href="#!" aria-expanded="false">
        <div class="icon_menu">
          <i class="fa fa-flag"></i>
        </div>
        <span>Reports</span>
      </a>
    </li> --}}
            <li class="">
                <a class="has-arrow <?php if ($active == 'setting') {
                    echo 'active';
                } ?>" href="#!" aria-expanded="<?php if ($active == 'setting') {
                    echo 'true';
                } else {
                    echo 'false';
                } ?>">
                    <div class="icon_menu">
                        <i class="fa fa-cogs"></i>
                    </div>
                    <span>Settings</span>
                </a>
                <ul <?php if ($active == 'setting') {
                    echo 'class="collapseshow"';
                } ?>>
                    <li><a class="<?php if ($active1 == 'countryList') {
                        echo 'active2';
                    } ?>" href="{{ url('/admin/countryList') }}">Countries</a></li>

                </ul>
            </li>
        </ul>
    </nav>
    <section class="main_content dashboard_part large_header_bg">
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="header_iner d-flex justify-content-between align-items-center">
                        <div class="sidebar_icon d-lg-none">
                            <i class="ti-menu"></i>
                        </div>
                        <div class="serach_field-area d-flex align-items-center">
                            <!-- <div class="search_inner">
                <form action="#">
                  <div class="search_field">
                    <input type="text" placeholder="Search here...">
                  </div>
                  <button type="submit"> <img src="{{ url('public/img/icon/icon_search.svg') }}" alt> </button>
                </form>
              </div>
              <span class="f_s_14 f_w_400 ml_25 white_text text_white">Apps</span> -->
                        </div>
                        <div class="header_right d-flex justify-content-between align-items-center">
                            <!-- <div class="header_notification_warp d-flex align-items-center">
                <li>
                  <a class="bell_notification_clicker nav-link-notify" href="#"> <img src="{{ url('public/img/icon/bell.svg') }}" alt>
                  </a>
                  <div class="Menu_NOtification_Wrap">
                    <div class="notification_Header">
                      <h4>Notifications</h4>
                    </div>
                    <div class="Notification_body">
                      <div class="single_notify d-flex align-items-center">
                        <div class="notify_thumb">
                          <a href="#"><img src="{{ url('public/img/staf/2.png') }}" alt></a>
                        </div>
                        <div class="notify_content">
                          <a href="#">
                            <h5>Cool Marketing </h5>
                          </a>
                          <p>Lorem ipsum dolor sit amet</p>
                        </div>
                      </div>
                      <div class="single_notify d-flex align-items-center">
                        <div class="notify_thumb">
                          <a href="#"><img src="{{ url('public/img/staf/4.png') }}" alt></a>
                        </div>
                        <div class="notify_content">
                          <a href="#">
                            <h5>Awesome packages</h5>
                          </a>
                          <p>Lorem ipsum dolor sit amet</p>
                        </div>
                      </div>
                      <div class="single_notify d-flex align-items-center">
                        <div class="notify_thumb">
                          <a href="#"><img src="{{ url('public/img/staf/3.png') }}" alt></a>
                        </div>
                        <div class="notify_content">
                          <a href="#">
                            <h5>what a packages</h5>
                          </a>
                          <p>Lorem ipsum dolor sit amet</p>
                        </div>
                      </div>
                      <div class="single_notify d-flex align-items-center">
                        <div class="notify_thumb">
                          <a href="#"><img src="{{ url('public/img/staf/2.png') }}" alt></a>
                        </div>
                        <div class="notify_content">
                          <a href="#">
                            <h5>Cool Marketing </h5>
                          </a>
                          <p>Lorem ipsum dolor sit amet</p>
                        </div>
                      </div>
                      <div class="single_notify d-flex align-items-center">
                        <div class="notify_thumb">
                          <a href="#"><img src="{{ url('public/img/staf/4.png') }}" alt></a>
                        </div>
                        <div class="notify_content">
                          <a href="#">
                            <h5>Awesome packages</h5>
                          </a>
                          <p>Lorem ipsum dolor sit amet</p>
                        </div>
                      </div>
                      <div class="single_notify d-flex align-items-center">
                        <div class="notify_thumb">
                          <a href="#"><img src="{{ url('public/img/staf/3.png') }}" alt></a>
                        </div>
                        <div class="notify_content">
                          <a href="#">
                            <h5>what a packages</h5>
                          </a>
                          <p>Lorem ipsum dolor sit amet</p>
                        </div>
                      </div>
                    </div>
                    <div class="nofity_footer">
                      <div class="submit_button text-center pt_20">
                        <a href="#" class="btn_1">See More</a>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <a class="CHATBOX_open nav-link-notify" href="#"> <img src="{{ url('public/img/icon/msg.svg') }}" alt> </a>
                </li>
              </div> -->
                            <div class="profile_info">
                                <div class="profileImg">
                                    <?php if (isset($admin)) { ?>
                                    <img src="{{ url('public/img') }}/{{ $admin->image }}" alt="Profile Image">
                                    <?php } ?>

                                    <div>
                                        <p><?= $admin->name ?></p>
                                        <p><?= $admin->email ?></p>
                                    </div>

                                    <!-- <p><?php if ($admin->userType == 1) {
                                        echo 'Admin';
                                    } elseif ($admin->userType == 2) {
                                        echo 'Staff';
                                    } ?></p> -->
                                </div>
                                <div class="profile_info_iner">
                                    <div class="profile_author_name">
                                        <div class="profileImg">
                                            <?php if (isset($admin)) { ?>
                                            <img src="{{ url('public/img') }}/{{ $admin->image }}"
                                                alt="Profile Image">
                                            <?php } ?>

                                            <div>
                                                <p><?= $admin->name ?></p>
                                            </div>

                                            <!-- <p><?php if ($admin->userType == 1) {
                                                echo 'Admin';
                                            } elseif ($admin->userType == 2) {
                                                echo 'Staff';
                                            } ?></p> -->
                                        </div>
                                        <!-- <h5><?= $admin->name ?></h5> -->
                                    </div>
                                    <div class="profile_info_details">
                                        <a href="{{ url('/admin/profile') }}/{{ base64_encode($admin->id) }}">My
                                            Profile </a>
                                        <!-- <a href="#">Settings</a> -->
                                        <a href="{{ url('/admin/logout') }}">Logout </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
