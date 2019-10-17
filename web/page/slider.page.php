<?php require(HEAD); ?>

<section class="bg-white">
    <div id="sliderBanner" class="rm-slider" data-slider-type="slider-banner">
        <div class="inner">
            <ul class="slides">
                <li class="slide" data-slide-number="1" data-slide-active="true">
                    <div class="s-content">
                    <h3 class="project-title white-fg">Banner Slide - Title 1</h3>
                    </div>
                    <div class="s-bg">
                        <img src="<?= ROOT; ?>web/image/demo/demo_gallery_lrg01.png" alt="demo-project-feature" class="full-width"/>
                    </div>
                </li>
                <li class="slide" data-slide-number="2" data-slide-active="false">
                    <div class="s-content">
                    <h3 class="project-title white-fg">Banner Slide - Title 2</h3>
                    </div>
                    <div class="s-bg">
                        <img src="<?= ROOT; ?>web/image/demo/demo_gallery_lrg02.png" alt="demo-project-feature" class="full-width"/>
                    </div>
                </li>
                <li class="slide" data-slide-number="3" data-slide-active="false">
                    <div class="s-content">
                    <h3 class="project-title white-fg">Banner Slide - Title 3</h3>
                    </div>
                    <div class="s-bg">
                        <img src="<?= ROOT; ?>web/image/demo/demo_gallery_lrg03.png" alt="demo-project-feature" class="full-width"/>
                    </div>
                </li>
                <li class="slide" data-slide-number="4" data-slide-active="false">
                    <div class="s-content">
                    <h3 class="project-title white-fg">Banner Slide - Title 4</h3>
                    </div>
                    <div class="s-bg">
                        <img src="<?= ROOT; ?>web/image/demo/demo_gallery_lrg04.png" alt="demo-project-feature" class="full-width"/>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section><br>
<section class="bg-white padding-v">
    <div id="sliderContent" class="rm-slider" data-slider-type="slider-content">
        <div class="inner">
            <ul class="slides text-center">
                <li class="slide" data-slide-number="1" data-slide-active="true">
                    <div class="s-content">
                        <div class="row column text-center">
                            <h3>Who are we?</h3>
                            <p>
                                Raremedia is a digital agency with services as diverse as our range of clients. We build relationships with our
                                clients to create effective, creative and strategic digital solutions that deliver.
                            </p>
                            <p>
                                We specialise in the world of digital - web, mobile &amp; app development, custom crafted CMS, e commerce, motion
                                graphics, video editing &amp; production, 3D modelling &amp; animation, viral marketing &amp; game development just to name a few.
                                We offer a complete service that encompasses strategy, branding, offline marketing material,
                                print requirements and hosting. We've got you covered.
                            </p>
                        </div>
                    </div>
                </li>
                <li class="slide" data-slide-number="2" data-slide-active="false">
                    <div class="s-content">

                        <div class="row column text-center">
                            <h2>Slide Grid Layout</h2>
                            <div class="row">
                                <div class="columns small-6 medium-4 large-3">
                                    <img src="<?= ROOT; ?>web/image/demo/demo_tile01.png" alt="img 01" />
                                    <p><h5>Title 01</h5>
                                    <span class="small">short description</span></p>
                                </div>
                                <div class="columns small-6 medium-4 large-3">
                                    <img src="<?= ROOT; ?>web/image/demo/demo_tile01.png" alt="img 02" />
                                    <p><h5>Title 02</h5>
                                    <span class="small">short description</span></p>
                                </div>
                                <div class="columns small-6 medium-4 large-3">
                                    <img src="<?= ROOT; ?>web/image/demo/demo_tile01.png" alt="img 03" />
                                    <p><h5>Title 03</h5>
                                     <span class="small">short description</span></p>
                                </div>
                                <div class="columns small-6 medium-4 large-3">
                                    <img src="<?= ROOT; ?>web/image/demo/demo_tile01.png" alt="img 04" />
                                    <p><h5>Title 04</h5>
                                    <span class="small">short description</span></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </li>
            </ul>
            <div>
        </div>
</section><br>
<section class="bg-white padding-v">
    <div id="sliderContentNumbers" class="rm-slider" data-slider-type="slider-content">
        <div class="inner">
            <ul class="slides">
                <li class="slide slide-1" data-slide-number="1" data-slide-active="true">
                    <div class="s-content">
                        <div class="row column text-center">
                            <h3 class="line01">1. Strategy consultation</h3>
                            <p class="line02">How : Our Approach </p>
                            <p class="line03">We dig deep to understand your goals, boundaries and target markets to develop unique creative solutions. This is articulated in a project proposal that considers all aspects of the project including strategy, costing &amp; timing.</p>
                        </div>
                    </div>
                </li>

                <li class="slide slide-2" data-slide-number="2" data-slide-active="false">
                    <div class="s-content">
                        <div class="row column text-center">
                            <h3 class="line01">2. Design Development</h3>
                            <p class="line02">How : Our Approach </p>
                            <p class="line03">Based on the foundations developed in stage one, the design stage translates this strategy into tangible visual solutions. This organic process generates many ideas, but only the most creative, stimulating design that we believe in, will be presented.</p>
                        </div>
                    </div>
                </li>

                <li class="slide slide-3" data-slide-number="3" data-slide-active="false">
                    <div class="s-content">
                        <div class="row column text-center">
                            <h3 class="line01">3. Review</h3>
                            <p class="line02">How : Our Approach </p>
                            <p class="line03">We like to work closely with our clients, gaining a detailed understanding of their customers. The review stage gives us an opportunity for us to share the passion of what we've designed and refine the concept together.</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section>
    <!-- content end -->
<?php require(FOOT); ?>
