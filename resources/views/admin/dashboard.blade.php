@include('admin.includes.header')

<style>
    .info-card {
        margin-bottom: 23px;
        border: 0;
    }

    .iconBorder {
        font-size: 90px;
        color: #f1f1f1;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        display: inline-block;
        color: #fff;
    }

    .valueWithName h3 {
        font-size: 32px;
        font-weight: 700;
        color: #fff;
    }

    .dash_dis {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dash_card {
        height: 145px;
        background-color: #fff;
        border-radius: 10px;
        position: relative;
        margin: 15px 0;
        box-shadow: 1px 3px 20px rgba(0, 0, 0, 0.2);
        padding: 20px;
        transition: all 100ms ease-in-out;
    }

    .dash_card:hover {
        transform: scale(1.04);
    }

    .dash_card a {
        height: 30px;
        width: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
        color: #000;
        /* float: right; */
        padding-top: 5px;
        background: #231f201a;
    }

    .dash_card h5 {
        font-weight: 600;
        line-height: 18px;
        color: #393939;
        font-size: 14px;
    }

    .dash_card .items_icon {
        font-size: 52px;
        position: absolute;
        right: 68px;
        bottom: 20px;
    }

    .dash_card .card_img {
        max-height: 75px;
        max-width: 75px;
        min-width: 50px;
        min-height: 50px;
    }

    .dash_card img {
        height: 100%;
        width: 100%;
        object-fit: contain;
    }

    .dash_card h3 {
        margin-bottom: 15px;
    }

    /* ---- for green color #1a9f53----- */
    .dash_green h3 {
        color: #1a9f53;
    }

    /* .dash_green {
        border-left: 4px solid #1a9f53;
    }

    .dash_green a {
        background: #1a9f53;
    } 
    
    .dash_green .items_icon {
        color: #1a9f5336; 
    }*/


    /* ---- for purple color #be0ee1----- */
    .dash_purple h3 {
        color: #be0ee1;
    }

    /*  .dash_purple {
        border-left: 4px solid #be0ee1;
    }

    .dash_purple a {
        background: #be0ee1;
    }

    .dash_purple .items_icon {
        color: #be0ee136;
    }

    /* ---- for blue color #2b77e5----- */
    .dash_blue h3 {
        color: #2b77e5;
    }

    /*.dash_blue {
        border-left: 4px solid #2b77e5;
    }

    .dash_blue a {
        background: #2b77e5;
    }

    .dash_blue .items_icon {
        color: #2b77e536;
    }

    /* ---- for yellow color #dd9718----- */
    .dash_yellow h3 {
        color: #dd9718;
    }

    /* .dash_yellow {
        border-left: 4px solid #dd9718;
    }

    .dash_yellow a {
        background: #dd9718;
    }

    .dash_yellow .items_icon {
        color: #dd971836;
    }

    /* ---- for dark-blue color #9f5064----- */
    .dash_dark_blue h3 {
        color: #9f5064;
    }

    /* .dash_dark_blue {
        border-left: 4px solid #9f5064;
    }

    .dash_dark_blue a {
        background: #9f5064;
    }

    .dash_dark_blue .items_icon {
        color: #9f506436;
    }

    /* ---- for maroon color #dd325c----- */
    .dash_maroon h3 {
        color: #dd325c;
    }

    /* .dash_maroon {
        border-left: 4px solid #dd325c;
    }

    .dash_maroon a {
        background: #dd325c;
    }

    .dash_maroon .items_icon {
        color: #dd325c36;
    }

    /* ---- for light-purple color #807af2----- */
    .dash_light_purple h3 {
        color: #807af2;
    }

    /*.dash_light_purple {
        border-left: 4px solid #807af2;
    }

    .dash_light_purple a {
        background: #807af2;
    }

    .dash_light_purple .items_icon {
        color: #807af236;
    } */
</style>

<div class="main_content_iner overly_inner">
    <div class="container-fluid p-0 ">
        <div class="row">
            <div class="col-12">
                <div class="page_title_box d-flex align-items-center justify-content-between">
                    <div class="page_title_left">
                        <h4 class="f_s_30 f_w_700 text_white">Dashboard</h4>
                    </div>
                    <!-- <a href="#" class="white_btn3">Create Report</a> -->
                    @if(Session::has('message'))
                    <p class="alert alert-success"><span style="font-weight: 600;"> Success !! </span>{{ Session::get('message') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <section class="dashboard">
        <div class="row">
            <!-- <div class="col-xxl-3 col-md-3">
                <div class="dash_card dash_green">
                    <h5>Total Cars</h5>
                    <div class="dash_dis">
                        <div>
                            <h3>{{$car}}</h3>
                            <a href="{{ url('admin/brands') }}"> <i class="fi fi-rr-angle-small-right"></i> </a>
                        </div>
                        <div class="card_img">
                            <img src="{{ url('public/img/dashboard/cars.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-xxl-3 col-md-3">
                <div class="dash_card dash_purple">
                    <h5>Total Brands</h5>
                    <div class="dash_dis">
                        <div>
                            <h3>{{$brand}}</h3>
                            <a href="{{ url('admin/brands') }}"> <i class="fi fi-rr-angle-small-right"></i> </a>
                        </div>
                        <div class="card_img">
                            <img src="{{ url('public/img/dashboard/brand.png') }}" alt="">
                        </div>
                    </div>
                    <!-- <i class="fi fi-br-steering-wheel items_icon"></i> -->
                </div>
            </div>
            <div class="col-xxl-3 col-md-3">
                <div class="dash_card dash_blue">
                    <h5>Total Categories</h5>
                    <div class="dash_dis">
                        <div>
                            <h3>{{$category}}</h3>
                            <a href="{{ url('admin/category') }}"> <i class="fi fi-rr-angle-small-right"></i> </a>
                        </div>
                        <div class="card_img">
                            <img src="{{ url('public/img/dashboard/category.png') }}" alt="">
                        </div>
                    </div>
                    <!-- <i class="fi fi-ss-category items_icon"></i> -->
                </div>
            </div>
            <div class="col-xxl-3 col-md-3">
                <div class="dash_card dash_yellow">
                    <h5>Total Service Types</h5>
                    <div class="dash_dis">
                        <div>
                            <h3>{{$service}}</h3>
                            <a href="{{ url('admin/serviceType') }}"> <i class="fi fi-rr-angle-small-right"></i> </a>
                        </div>
                        <div class="card_img">
                            <img src="{{ url('public/img/dashboard/service-type.png') }}" alt="">
                        </div>
                    </div>
                    <!-- <i class="fi fi-br-tools items_icon"></i> -->
                </div>
            </div>
            <div class="col-xxl-3 col-md-3">
                <div class="dash_card dash_dark_blue">
                    <h5>Total Agents</h5>
                    <div class="dash_dis">
                        <div>
                            <h3>{{$agent}}</h3>
                            <a href="{{ url('admin/agent') }}"> <i class="fi fi-rr-angle-small-right"></i> </a>
                        </div>
                        <div class="card_img">
                            <img src="{{ url('public/img/dashboard/agent.png') }}" alt="">
                        </div>
                    </div>
                    <!-- <i class="fi fi-ss-user-headset items_icon"></i> -->
                </div>
            </div>
            <div class="col-xxl-3 col-md-3">
                <div class="dash_card dash_light_purple">
                    <h5>Total Deals</h5>
                    <div class="dash_dis">
                        <div>
                            <h3>{{$deals}}</h3>
                            <a href="{{ url('admin/dealList') }}"> <i class="fi fi-rr-angle-small-right"></i> </a>
                        </div>
                        <div class="card_img">
                            <img src="{{ url('public/img/dashboard/deals.png') }}" alt="">
                        </div>
                    </div>
                    <!-- <i class="fi fi-sr-handshake items_icon"></i> -->
                </div>
            </div>
            <div class="col-xxl-3 col-md-3">
                <div class="dash_card dash_maroon">
                    <h5>Total User</h5>
                    <div class="dash_dis">
                        <div>
                            <h3>{{$user}}</h3>
                            <a href="{{ url('admin/userList') }}"> <i class="fi fi-rr-angle-small-right"></i> </a>
                        </div>
                        <div class="card_img">
                            <img src="{{ url('public/img/dashboard/user.png') }}" alt="">
                        </div>
                    </div>
                    <!-- <i class="fi fi-sr-user items_icon"></i> -->
                </div>
            </div>
        </div>
    </section>
</div>

@include('admin.includes.footer')