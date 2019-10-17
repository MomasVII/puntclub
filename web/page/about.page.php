<?php require(HEAD); ?>

    <section class="row title-bar">
        <h2>About Us</h2>
        <h1>descriptive sub h1 text</h1>
    </section>
    <section class="row section-1 bg-white padding-v">
        <div class="columns small-12 medium-8">
            <h2>Sub Title</h2>
            <hr/>
            <div class="row">
                <p class="columns small-12 medium-12 large-6">
                    <strong>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</strong><br/>
                    Ut enim ad minim veniam, quis nostrud
                    exercitation ullamco laboris nisi ut aliquip.
                </p>
                <p class="columns small-12 medium-12 large-6">
                    irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia anim id est laborum.
                </p>
            </div>
            <br/>
            <div class="progress" role="progressbar" tabIndex="0" aria-valuenow="25" aria-valuemin="0" aria-valuetext="50 percent" aria-valuemax="100">
                <div class="progress-meter">
                    <p class="progress-meter-text">25%</p>
                </div>
            </div>
            <div class="success progress">
                <div class="progress-meter">
                    <p class="progress-meter-text">50%</p>
                </div>
            </div>
            <div class="warning progress">
                <div class="progress-meter">
                    <p class="progress-meter-text">70%</p>
                </div>
            </div>
            <div class="alert progress">
                <div class="progress-meter">
                    <p class="progress-meter-text">90%</p>
                </div>
            </div>
        </div>
        <div class="columns small-12 medium-4">
            <div class="orbit" role="region" aria-label="photo gallery" data-orbit data-use-m-u-i="false">
                <ul class="orbit-container">
                    <li class="is-active orbit-slide">
                        <img class="orbit-image" src="<?= ROOT; ?>web/image/demo/demo_tile01.png" alt="img 01" />
                    </li>
                    <li class="orbit-slide">
                        <img class="orbit-image" src="<?= ROOT; ?>web/image/demo/demo_tile02.png" alt="img 02" />
                    </li>
                    <li class="orbit-slide">
                        <img class="orbit-image" src="<?= ROOT; ?>web/image/demo/demo_tile03.png" alt="img 03" />
                    </li>
                    <li class="orbit-slide">
                        <img class="orbit-image" src="<?= ROOT; ?>web/image/demo/demo_tile04.png" alt="img 04" />
                    </li>
                    <li class="orbit-slide">
                        <img class="orbit-image" src="<?= ROOT; ?>web/image/demo/demo_tile05.png" alt="img 05" />
                    </li>
                    <li class="orbit-slide">
                        <img class="orbit-image" src="<?= ROOT; ?>web/image/demo/demo_tile06.png" alt="img 06" />
                    </li>
                </ul>
                Add custom gallery component
            </div>
        </div>
    </section>
    <section class="row section-2 bg-white padding-v">
        <div class="columns small-12 medium-8">
            <div class="row collapse">
                <div class="medium-3 columns">
                    <ul class="tabs vertical" id="example-vert-tabs" data-tabs>
                        <li class="tabs-title is-active"><a href="#panel1v" aria-selected="true">LISTS</a></li>
                        <li class="tabs-title"><a href="#panel2v">HEADINGS</a></li>
                        <li class="tabs-title"><a href="#panel3v">DEFAULT BUTTONS</a></li>
                        <li class="tabs-title"><a href="#panel4v">DYNAMIC BUTTONS</a></li>
                        <li class="tabs-title"><a href="#panel5v">ALERTS</a></li>
                        <li class="tabs-title"><a href="#panel6v">VIDEO RESPONSIVE</a></li>
                    </ul>
                </div>
                <div class="medium-9 columns">
                    <div class="tabs-content vertical" data-tabs-content="example-vert-tabs">
                        <div class="row tabs-panel is-active" id="panel1v">
                            <div class="columns small-12 medium-6">
                                <h4>Unordered List</h4>
                                <ul>
                                    <li>List item with a much longer description or more content.</li>
                                    <li>List item</li>
                                    <li>List item
                                        <ul>
                                            <li>Nested list item</li>
                                            <li>Nested list item</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="columns small-12 medium-6">
                                <h4>Ordered List</h4>
                                <ol>
                                    <li>Cheese (essential)</li>
                                    <li>Pepperoni</li>
                                    <li>Bacon
                                        <ol>
                                            <li>Normal bacon</li>
                                            <li>Canadian bacon</li>
                                        </ol>
                                    </li>
                                    <li>Sausage</li>
                                </ol>
                            </div>
                        </div>
                        <div class="tabs-panel" id="panel2v">
                            <h1>h1. Very large header.</h1>
                            <h2>h2. Large header.</h2>
                            <h3>h3. Medium header.</h3>
                            <h4>h4. Moderate header.</h4>
                            <h5>h5. Small header.</h5>
                            <h6>h6. Tiny header.</h6>
                        </div>
                        <div class="tabs-panel" id="panel3v">
                            <h3>Default Buttons</h3>
                            <div class="button-group">
                                <a class="button primary" href="#">Primary</a>
                                <a class="button secondary" href="#">Secondary</a>
                                <a class="button success" href="#">Success</a>
                                <a class="button alert" href="#">Alert</a>
                                <a class="button warning" href="#">Warning</a>
                            </div>
                            <h3>Outlined Buttons</h3>
                            <div class="button-group">
                                <button class="hollow button" href="#">Primary</button>
                                <button class="hollow button secondary" href="#">Secondary</button>
                                <button class="hollow button success" href="#">Success</button>
                                <button class="hollow button alert" href="#">Alert</button>
                                <button class="hollow button warning" href="#">Warning</button>
                                <button class="hollow button" href="#" disabled>Disabled</button>
                            </div>
                        </div>
                        <div class="tabs-panel" id="panel4v">
                            <h3>Scaled Buttons</h3>
                            <a class="button tiny" href="#">So Tiny</a>
                            <a class="button small" href="#">So Small</a>
                            <a class="button" href="#">So Basic</a>
                            <a class="button large" href="#">So Large</a>
                            <a class="button expanded" href="#">Such Expand</a>
                            <a class="button small expanded" href="#">Wow, Small Expand</a>
                            <h3>Dropdown Arrows</h3>
                            <button class="dropdown button tiny ">Dropdown Button</button>
                            <button class="dropdown button small ">Dropdown Button</button>
                            <button class="dropdown button">Dropdown Button</button>
                            <button class="dropdown button large ">Dropdown Button</button>
                            <button class="dropdown button expanded ">Dropdown Button</button>

                        </div>
                        <div class="tabs-panel" id="panel5v">
                            <div class="button-group">
                                <button type="button" class="button alert-box notice">Notice</button>
                                <button type="button" class="button alert-box error">Alert</button>
                                <button type="button" class="button alert-box success">Success</button>
                                <button type="button" class="button alert-box warning">Warning</button>
                                <button type="button" class="button alert-box notice">Reset</button>
                            </div>
                        </div>
                        <div class="tabs-panel" id="panel6v">
                            <div class="responsive-embed">
                                <iframe width="420" height="315" src="https://www.youtube.com/embed/V9gkYw35Vws" frameBorder="0" allowFullScreen></iframe>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="columns small-12 medium-4">
            <div class="row column small-up-1 medium-up-2 large-up-2">
                <ul class="accordion" data-responsive-accordion-tabs="tabs medium-accordion large-tabs">
                    <li class="accordion-item is-active" data-accordion-item>
                        <a href="#p01" class="accordion-title">BADGES</a>
                        <div id="p01" class="accordion-content" data-tab-content>
                            <h4>Badges</h4>
                            <span class="secondary badge">7</span>
                            <span class="success badge">27</span>
                            <span class="alert badge">A</span>
                            <span class="warning badge">B</span>
                        </div>
                    </li>
                    <li class="accordion-item" data-accordion-item>
                        <a href="#p02" class="accordion-title">BREADCRUMB</a>
                        <div id="p02" class="accordion-content" data-tab-content>
                            <h4>Breadcrumb</h4>
                            <nav aria-label="You are here:" role="navigation">
                                <ul class="breadcrumbs">
                                    <li><a href="#">Home</a></li>
                                    <li class="disabled"><span>About</span></li>
                                    <li><a href="#">Contact Us</a></li>
                                </ul>
                            </nav>
                        </div>
                    </li>
                    <li class="accordion-item" data-accordion-item>
                        <a href="#p03" class="accordion-title">PAGINATION</a>
                        <div id="p03" class="accordion-content" data-tab-content>
                            <h4>Pagination</h4>
                            <ul class="pagination" role="navigation" aria-label="Pagination">
                                <li class="disabled">Previous <span class="show-for-sr">page</span></li>
                                <li class="current"><span class="show-for-sr">You're on page</span> 1</li>
                                <li><a href="#0" aria-label="Page 2">2</a></li>
                                <li><a href="#0" aria-label="Page 3">3</a></li>
                                <li class="ellipsis" aria-hidden="true"></li>
                                <li><a href="#0" aria-label="Page 12">12</a></li>
                                <li><a href="#0" aria-label="Page 13">13</a></li>
                                <li><a href="#0" aria-label="Next page">Next <span class="show-for-sr">page</span></a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div id="brandCarousel" class="rm-carousel">
            <div class="inner">
                <ul class="slides text-center">
                    <li class="slide slide-7" data-slide-number="7">
                        <img class="brand-logo" src="<?= ROOT; ?>web/image/demo/logo-perthmint.png" alt="Perth Mint"/>
                    </li>
                    <li class="slide active slide-1" data-slide-number="1">
                        <img class="brand-logo" src="<?= ROOT; ?>web/image/demo/logo-tabcorp.png" alt="Tabcorp"/>
                    </li>
                    <li class="slide slide-2" data-slide-number="2">
                        <img class="brand-logo" src="<?= ROOT; ?>web/image/demo/logo-post.png" alt="Autralia Post"/>
                    </li>
                    <li class="slide slide-3" data-slide-number="3">
                        <img class="brand-logo" src="<?= ROOT; ?>web/image/demo/logo-philipmorris.png" alt="Philip Morris Limited"/>
                    </li>
                    <li class="slide slide-4" data-slide-number="4">
                        <img class="brand-logo" src="<?= ROOT; ?>web/image/demo/logo-keno.png" alt="Keno"/>
                    </li>
                    <li class="slide slide-5" data-slide-number="5">
                        <img class="brand-logo" src="<?= ROOT; ?>web/image/demo/logo-vicstatgov.png" alt="Victorian State Government"/>
                    </li>
                    <li class="slide slide-6" data-slide-number="6">
                        <img class="brand-logo" src="<?= ROOT; ?>web/image/demo/logo-cottons.png" alt="Cottons"/>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    
<?php require(FOOT); ?>
