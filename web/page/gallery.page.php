<?php require(HEAD); ?>
    <!-- FIXME: preloader
    <div id="loader_container" style="/*! display: none; */">
        <div id="overlay">
            <div id="modal">
                <div id="loader_content">
                    <div id="loader_percentage">0%</div>
                </div>
            </div>
        </div>
    </div>-->
    <section class="row title-bar">
        <h2>Gallery</h2>
        <h1>descriptive sub h1 text</h1>
    </section>

    <section class="row gallery-thumbnail padding-v">
        <div class="columns column-block small-6 medium-4" itemprop="associatedMedia" itemscope>
            <a href="<?= ROOT; ?>web/image/demo/demo_gallery_lrg01.png" itemprop="contentUrl" data-size="1365x768" data-size-sm="1024x576" data-href="<?= ROOT; ?>web/image/demo/demo_gallery_sm01.png">
                <img src="<?= ROOT; ?>web/image/demo/demo_gallery_sm01.png" itemprop="thumbnail" alt="Photo 01"/>
            </a>
        </div>
        <div class="columns column-block small-6 medium-4" itemprop="associatedMedia" itemscope>
            <a href="<?= ROOT; ?>web/image/demo/demo_gallery_lrg02.png" itemprop="contentUrl" data-size="1365x768" data-size-sm="1024x576" data-href="<?= ROOT; ?>web/image/demo/demo_gallery_sm02.png">
                <img src="<?= ROOT; ?>web/image/demo/demo_gallery_sm02.png" itemprop="thumbnail" alt="Photo 02"/>
            </a>
        </div>
        <div class="columns column-block small-6 medium-4" itemprop="associatedMedia" itemscope>
            <a href="<?= ROOT; ?>web/image/demo/demo_gallery_lrg03.png" itemprop="contentUrl" data-size="1365x768" data-size-sm="1024x576" data-href="<?= ROOT; ?>web/image/demo/demo_gallery_sm03.png">
                <img src="<?= ROOT; ?>web/image/demo/demo_gallery_sm03.png" itemprop="thumbnail" alt="Photo 03"/>
            </a>
        </div>
        <div class="columns column-block small-6 medium-4" itemprop="associatedMedia" itemscope>
            <a href="<?= ROOT; ?>web/image/demo/demo_gallery_lrg04.png" itemprop="contentUrl" data-size="1365x768" data-size-sm="1024x576" data-href="<?= ROOT; ?>web/image/demo/demo_gallery_sm04.png">
                <img src="<?= ROOT; ?>web/image/demo/demo_gallery_sm04.png" itemprop="thumbnail" alt="Photo 04"/>
            </a>
        </div>
        <div class="columns column-block small-6 medium-4" itemprop="associatedMedia" itemscope>
            <a href="<?= ROOT; ?>web/image/demo/demo_gallery_lrg05.png" itemprop="contentUrl" data-size="1365x768" data-size-sm="1024x576" data-href="<?= ROOT; ?>web/image/demo/demo_gallery_sm05.png">
                <img src="<?= ROOT; ?>web/image/demo/demo_gallery_sm05.png" itemprop="thumbnail" alt="Photo 05"/>
            </a>
        </div>
        <div class="columns column-block small-6 medium-4" itemprop="associatedMedia" itemscope>
            <a href="<?= ROOT; ?>web/image/demo/demo_gallery_lrg06.png" itemprop="contentUrl" data-size="1365x768" data-size-sm="1024x576" data-href="<?= ROOT; ?>web/image/demo/demo_gallery_sm06.png">
                <img src="<?= ROOT; ?>web/image/demo/demo_gallery_sm06.png" itemprop="thumbnail" alt="Photo 06"/>
            </a>
        </div>
    </section>

    <!-- Gallery Overview - Start-->
    <!-- Root element of PhotoSwipe. Must have class pswp. -->
    <!-- TODO IF Gallery is required on site please move this code into the foot include -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <!-- Background of PhotoSwipe.
             It's a separate element, as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>
        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">
            <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
            <!-- don't modify these 3 pswp__item elements, data is added later on. -->
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
            <div class="pswp__ui pswp__ui--hidden">

                <div class="pswp__top-bar">
                    <!--  Controls are self-explanatory. Order can be changed. -->
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <!--<button class="pswp__button pswp__button--share" title="Share"></button>-->
                    <!--<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>-->
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                    <!-- element will get class pswp__preloader--active when preloader is running -->
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>

        </div>
    </div>
    <!-- Gallery Overview - End-->
<?php require(FOOT); ?>
