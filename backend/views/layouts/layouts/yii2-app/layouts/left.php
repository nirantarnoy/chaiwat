<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
              <?php use yii\web\Session;

              $session = new Session();
              $session->open(); ?>
                <p><?php !Yii::$app->user->isGuest? Yii::$app->user->identity->username:''?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <!-- <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form> -->
        <!-- /.search form -->


       <?php if(1 == 1):?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    //['label' => '', 'options' => ['class' => 'header']],
                    ['label' => 'หน้าแรก','icon'=>'dashboard', 'url' => ['/dashboard']],
                    ['label' => 'ตั้งค่าร้าน','icon'=>'cogs', 'url' => ['/shop']],
                  // [
                  //       'label' => 'ตั้งค่าระบบ',
                  //       'icon' => 'cog',
                  //       'url' => "#",
                  //       'items' => [
                  //           ['label' => 'คำนำหน้า', 'icon' => 'file-code-o', 'url' => ['/prefixname'],],
                  //           ['label' => 'ประเภทไฟล์แนบ', 'icon' => 'folder-open', 'url' => ['/filetype'],],
                  //           ['label' => 'ช่องทางชำระเงิน', 'icon' => 'cc-mastercard', 'url' => ['/paymentchannel'],],
                  //           //['label' => 'สิทธิ์การใช้งาน', 'icon' => 'registered', 'url' => ['/userrole'],],
                  //         //  ['label' => 'กำหนดสิทธิ์การใช้งาน', 'icon' => 'cube', 'url' => ['/assignrole'],],
                  //       ],
                  //   ], 
                    [
                        'label' => 'ผู้ใช้งาน',
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            ['label' => 'กลุ่มผู้ใช้งาน', 'icon' => 'file-code-o', 'url' => ['/usergroup'],],
                            ['label' => 'แฟ้มผู้ใช้งาน', 'icon' => 'user', 'url' => ['/user'],],
                            ['label' => 'สิทธิ์การใช้งาน', 'icon' => 'registered', 'url' => ['/userrole'],],
                          //  ['label' => 'กำหนดสิทธิ์การใช้งาน', 'icon' => 'cube', 'url' => ['/assignrole'],],
                        ],
                    ], 
                    
                     [
                        'label' => 'พนักงาน',
                        'icon' => 'newspaper-o',
                        'url' => '#',
                        'items' => [
                           
                            ['label' => 'ตำแหน่ง', 'icon' => 'file-code-o', 'url' => ['/position'],],
                            ['label' => 'พนักงาน', 'icon' => 'user', 'url' => ['/employee'],],
                           
                        ],
                    ],  
                    [
                        'label' => 'สินค้า',
                        'icon' => 'cube',
                        'url' => '#',
                        'items' => [
                           
                            ['label' => 'ประภทสินค้า', 'icon' => 'cubes', 'url' => ['/category'],],
                            ['label' => 'ยี่ห้อ', 'icon' => 'cube', 'url' => ['/brand'],],
                            ['label' => 'รุ่น', 'icon' => 'cube', 'url' => ['/productmodel'],],
                            ['label' => 'รหัสสินค้า', 'icon' => 'cube', 'url' => ['/product'],],
                            ['label' => 'หน่วยนับ', 'icon' => 'magnet', 'url' => ['/unit'],],
                           
                        ],
                    ],  
                    [
                        'label' => 'ซื้อสินค้า',
                        'icon' => 'shopping-cart',
                        'url' => ['#'],
                        'items' => [
                           
                             ['label' => 'กลุ่มผู้ขาย', 'icon' => 'users', 'url' => ['/vendorgroup'],],
                             ['label' => 'ผู้ขาย', 'icon' => 'user', 'url' => ['/vendor'],],
                             ['label' => 'ใบสั่งซื้อ', 'icon' => 'shopping-cart', 'url' => ['/purchaseorder'],],
                           
                        ],
                    ],  
                    [
                        'label' => 'ขายสินค้า',
                        'icon' => 'money',
                        'url' => ['/sale'],
                        'items' => [
                           
                             ['label' => 'ประเภทลูกค้า', 'icon' => 'square-o', 'url' => ['/customertype'],],
                             ['label' => 'ลูกค้า', 'icon' => 'street-view', 'url' => ['/customer'],],
                             ['label' => 'ขายสินค้า', 'icon' => 'money', 'url' => ['/sale'],],
                             ['label' => 'ตลาด', 'icon' => 'map-pin', 'url' => ['/market'],],
                           
                        ],
                    ],  
                    [
                        'label' => 'จัดการสินค้า',
                        'icon' => 'navicon',
                        'url' => '#',
                        'items' => [
                           
                            ['label' => 'คลังสินค้า', 'icon' => 'archive', 'url' => ['/warehouse'],],
                            ['label' => 'เติมสินค้า', 'icon' => 'refresh', 'url' => ['/issuetable'],],
                            ['label' => 'รายการอนุมัติเติมสินค้า', 'icon' => 'arrow-circle-up', 'url' => ['/issueapprove'],],
                            ['label' => 'สินค้าคงคลัง', 'icon' => 'cube', 'url' => ['/stockbalance'],],
                           
                        ],
                    ],  
                    [

                        'label' => 'บันทึกรายการประจำวัน',
                        'icon' => 'folder-open',
                        'url' => '#',
                        'items' => [
                            ['label' => 'หัวข้อรายจ่าย', 'icon' => 'cubes', 'url' => ['/expense'],],
                            ['label' => 'บันทึกรายจ่าย', 'icon' => 'cube', 'url' => ['/transaction'],],
                           
                        ],
                    ],  
                     [

                        'label' => 'รายงาน',
                        'icon' => 'bar-chart',
                        'url' => '#',
                        'items' => [
                           
                            // ['label' => 'ประภทสินค้า', 'icon' => 'cubes', 'url' => ['/category'],],
                            // ['label' => 'รหัสสินค้า', 'icon' => 'cube', 'url' => ['/product'],],
                           
                        ],
                    ],  
                    
                ],
            ]
        ) ?>
    <?php elseif($session['groupid']==2 && $session['roleaction']==1):?>
            <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    //['label' => '', 'options' => ['class' => 'header']],
                    ['label' => 'หน้าแรก','icon'=>'dashboard', 'url' => ['/dashboard']],
                  //  ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'ผลิตภัณฑ์',
                        'icon' => 'cubes',
                        'url' => '#',
                        'items' => [
                            ['label' => 'กลุ่มผลิตภัณฑ์', 'icon' => 'file-code-o', 'url' => ['/category'],],
                            ['label' => 'แฟ้มผลิตภัณฑ์', 'icon' => 'cube', 'url' => ['/product'],],
                            //['label' => 'หน่วยนับ', 'icon' => 'magnet', 'url' => ['/unit'],],
                        ],
                    ],
                    [
                        'label' => 'บันทึกรายการประจำวัน',
                        'icon' => 'retweet',
                        'url' => ['/transaction'],
                        'items' => [
                          ['label' => 'ประเภทการติดต่อ', 'icon' => 'filter', 'url' => ['/contacttype'],],
                          ['label' => 'ประเภทรถ', 'icon' => 'truck', 'url' => ['/cartype'],],
                        //  ['label' => 'แจ้งรถเข้า', 'icon' => 'arrow-right', 'url' => ['/journal/journalin'],],
                          ['label' => 'แจ้งรถเข้า - ออก', 'icon' => 'random', 'url' => ['/journal'],],
                          ['label' => 'รับสินค้า', 'icon' => 'edit', 'url' => ['/transaction'],],
                          ['label' => 'รายการแจ้งเตือน', 'icon' => 'bell', 'url' => ['/notification'],],
                      ],
                    ],
                    [
                        'label' => 'รายงาน',
                        'icon' => 'pie-chart',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Report1', 'icon' => 'bar-chart', 'url' => ['/gii'],],
                            ['label' => 'Report2', 'icon' => 'bar-chart', 'url' => ['/debug'],],
                            ['label' => 'Report3', 'icon' => 'bar-chart', 'url' => ['/debug'],],
                            ['label' => 'Report4', 'icon' => 'bar-chart', 'url' => ['/debug'],],
                        ],
                    ],
                ],
            ]
        ) ?>
    <?php else:?>

        <?= dmstr\widgets\Menu::widget(
            // [
            //     'options' => ['class' => 'sidebar-menu'],
            //     'items' => [
            //         //['label' => '', 'options' => ['class' => 'header']],
            //         [
            //             'label' => 'บันทึกรายการประจำวัน',
            //             'icon' => 'retweet',
            //             'url' => '#',
            //             'items' => [
            //               // ['label' => 'ประเภทการติดต่อ', 'icon' => 'filter', 'url' => ['/contacttype'],],
            //               // ['label' => 'ประเภทรถ', 'icon' => 'truck', 'url' => ['/cartype'],],
            //             //  ['label' => 'แจ้งรถเข้า', 'icon' => 'arrow-right', 'url' => ['/journal/journalin'],],
            //               ['label' => 'แจ้งรถเข้า - ออก', 'icon' => 'random', 'url' => ['/journal'],],
            //                ['label' => 'รายการแจ้งเตือน', 'icon' => 'random', 'url' => ['/notification/showlist'],],
            //           ],
            //         ],
                    
            //     ],
            // ]
        ) ?>

    <?php endif;?>

    </section>

</aside>
