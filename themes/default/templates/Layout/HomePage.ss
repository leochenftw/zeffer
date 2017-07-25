<nav id="nextsection">
            <div class="container_12">
                <ul>
                    <li class="active"><a href="#ciders">Our Ciders</a></li>
                    <% loop ListCiders %>
                    <li><a href="#{$IdHash}">$Title</a></li>
                    <% end_loop %>
                    <li><a href="#statement">Story</a></li>
                    <li><a href="#matakana">Where we make it</a></li>
                    <li><a href="#team">Our Team</a></li>
                    <li><a href="#madewithlove">Our Process</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="#stockists">Stockists</a></li>
                    <li><a href="#carousel">Back to top</a></li>
                </ul>
            </div>
        </nav>

        <header id="header">
            <nav class="container_12">
                <ul>
                    <li class="grid_2 home"><a href="#">Home</a></li>
                    <li class="grid_1 alpha ciders"><a href="#ciders">Ciders</a></li>
                    <li class="grid_2 centre story"><a href="#statement">Story</a></li>
                    <li id="logo" class="grid_3 home"><a href="#">Zeffer</a></li>
                    <li class="grid_2 centre contact"><a href="#contact">Contact</a></li>
                    <li class="grid_2 omega right stockists"><a href="#stockists">Stockist<em>s</em><span> Map</span></a></li>
                </ul>
            </nav>
        </header>

        <section id="carousel" class="fullheight">

            <ul>
                <li class="_1 active"><span><b>Zeffer Brewing co.</b> We make delicious cider in Matakana, NZ</span></li>
            </ul>

            <nav class="container">
                <a href="#" class="previous">previous</a> <a href="#" class="next">next</a>
            </nav>
        </section>

        <section id="ciders" class="clearfix">
            <div class="container_12 overflow">
                <header>
                    <h1>Our Delicious Ciders</h1>
                    <nav>
                        <ul>
                            <% loop ListCiders %>
                            <li><a href="#{$IdHash}">$Title</a></li>
                            <% end_loop %>
                            <!--
                            <li><a href="#red-apple">Red Apple</a></li>
                            <li><a href="#apple">Crisp Apple</a></li>
                            <li><a href="#pear">Juicy Pear</a></li>
                            <li><a href="#zesty-citrus">Zesty Citrus</a></li>
                            <li><a href="#hopped-up-pippin">Hopped Up Pippin</a></li>
                            <li><a href="#slack-ma-girdle">Slack Ma Girdle</a></li>
                            -->
                        </ul>
                    </nav>
                </header>

                <div class="ciders">
                    <!-- new cider -->
                    <% loop ListCiders %>
                    $Me
                    <% end_loop %>
                    <!-- new cider -->
                </div>

            </div>
        </section>

        <section id="statement" class="background fullheight">
            <div id="youtube-holder" class="hide">
                <div class="responsive-iframe">
                    <iframe width="960" height="540" src="https://www.youtube.com/embed/i8H9fZFQ3xs" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            <div class="panel">
                <div class="container_12">
                    <div class="grid_8"><h1>The Zeffer Story</h1></div>
                    <div class="grid_4"><a id="show-youtube" class="flag-button" href="https://youtu.be/i8H9fZFQ3xs" target="_blank">Watch how we make Zeffer</a></div>
                </div>

                <div class="container_12 border-bottom">
                    <div class="grid_6">
                        <h2>OUR JOURNEY BEGAN IN MATAKANA IN 2009 WHEN OUR FOUNDER SAM DECIDED TO TRY HIS WINE-MAKING HAND AT MAKING CIDER. AFTER EXTENSIVE RESEARCH WE KNEW THE STYLE OF CIDER WE LIKED BEST. NOT JUST ANY OLD ‘MADE FROM CONCENTRATE’ CIDER, WE WANTED TO MAKE REAL CIDER FROM REAL NEW ZEALAND FRUIT WITH PATIENCE, CRAFT AND QUALITY.</h2>
                        <p>We knew that the final product would taste its best if we started with the best ingredients so we scoured the country to find specific apple varieties from orchards around New Zealand.</p>

                        <p>After long wintery nights crushing, an exploding fruit press, experimental brews and many hours spent hand bottling, we had our first 3,000L batch ready for release in the Spring of 2009. </p>
                    </div>
                    <div class="grid_6">
                        <p>We sold it exclusively through our local Matakana Farmers market and were rewarded with great feedback, eager buyers and steady growth which allowed us to build our own cidery in Silverdale in 2012.</p><p>The thirst for Zeffer has continued to increase and now reaches offshore. To facilitate our next stage of growth we are building a new cidery in the beautiful Hawke's Bay (beside our apples). </p>
                        <p>We remain faithful to our simple vision and ethos of making cider that we are both proud to put our name on and love to drink. We are passionate about real New Zealand cider and it’s  a privilige to be able to share it with you.</p>
                        <strong>Cheers, <br />
                        The Team at Zeffer</strong>
                    </div>
                </div>
            </div>
        </section>

        <section id="team" class="background fullheight">
            <div class="panel">
                <div class="container_12">
                    <h1 class="grid_12">
                        <% loop TeamMembers %><% if First %><% else %><% if Last %> & <% else %>, <% end_if %><% end_if %>$Name<% end_loop%>
                    </h1>
                    <% loop TeamMembers %>
                        <div class="grid_3">
                            $Portrait.SetWidth(220)
                            $Blurb
                        </div>
                    <% end_loop %>
                </div>
            </div>
        </section>

        <section id="madewithlove" class="background fullheight">
            <div class="panel">
                <div class="container_12">
                    <h1>Made with love &amp; craft</h1>
                    <img src="$ThemeDir/img/made-with-love-and-craft.jpg" alt="Made with love and craft" width="940" height="1080" />
                </div>
            </div>
        </section>

        <section id="contact" class="background fullheight">
            <div class="panel">
                <div class="container_12">
                    <h1 class="grid_12">Contact Zeffer</h1>
                    <div class="grid_7 after border-bottom">
                        <div class="grid_2 alpha">
                            <h2>Postal Address</h2>
                            <p>4i Titan Place,<br>
                            Silverdale,<br>
                            Auckland<br>
                            <a href="tel:09 215 6028">(09) 215 6028</a></p>
                            <h2>Physical Address</h2>
                            <p>1747 Korokipo Rd, RD3, Fernhill<br>
                            Hawke’s Bay 4185<br>
                            <a href="tel:06 650 1836">(06) 650 1836</a></p>
                        </div>
                        <div class="grid_2">
                            <h2>Sales &amp; Marketing</h2>
                            <p><a href="mailto:josh@zeffer.co.nz">josh@zeffer.co.nz</a><br/>
                            <a href="tel:021 223 2800">(021) 223 2800</a></p>
                            <h2>NZ Distributor</h2>
                            <p><a href="http://quenchcollective.co.nz" target="_blank">Quench Collective</a><br />
                            <a href="tel:0800 946 326">0800 946 326</a></p>
                        </div>
                        <div class="grid_3 tail">
                            <h2>Visit us for Cider</h2>
                            <p>Cellar door sales available at our Tap Room, located at our cidery in Silverdale.</p>
                            <h2>New cellar door opening in October!</h2>
                            <p><a style="border-bottom: 1px solid rgba(85,65,27, 0.2);" href="https://confirmsubscription.com/h/d/70DB7D1922B72023" target="_blank">Click here to keep updated</a></p>
                        </div>
                        <img src="$ThemeDir/img/map.gif" width="537" height="224" alt="" class="map" />
                    </div>
                    <div class="grid_4">
                        <h2>Chat with Zeffer online</h2>
                        <ul class="contact">
                            <li class="facebook"><a href="https://www.facebook.com/zeffercider" target="_blank">facebook.com/zeffercider</a></li>
                            <li class="twitter"><a href="https://twitter.com/zeffercider" target="_blank">@zeffercider</a></li>
                            <li class="instagram"><a href="http://instagram.com/zeffercider" target="_blank">zeffercider</a></li>
                            <li class="wechat"><a id="pop-qr" href="/#">ZefferCider</a></li>
                            <li class="email"><a href="mailto:info@zeffer.co.nz">info@zeffer.co.nz</a></li>
                            <li class="newsletter"><a href="https://confirmsubscription.com/h/d/70DB7D1922B72023" target="_blank">Sign up to our Newsletter</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section id="stockists" class="fullheight background">
            <div class="panel">
                <header>
                    <div class="container_12 clearfix">
                        <div class="grid_8">
                            <h1>Stockists</h1>
                            <a href="http://www.vineonline.co.nz/buy-wine/cider-wine" target="_blank" class="shop">Buy online NZ</a>
                            <a href="https://www.thecraftbeermarket.com.au/online-cider-shop/by-brewery/zeffer-cider-co.html" target="_blank" class="shop">Buy online AUS</a>
                            <!-- <a href="http://www.shipcider.com/collections/buy-hard-cider-online-shipcider-com/Zeffer" target="_blank" class="shop">Buy online USA</a> -->
                        </div>

                        <form action="" class="grid_4">
                            <select name="search" id="search" data-placeholder="Choose your City/Town">
                                <option></option>
                            <% loop $getCities %>
                                <% if $Cities %>
                                    <% loop $Cities %>
                                        <% if $hasStockists %>
                                    <optgroup label="$Name">
                                    <% if Suburbs %>
                                        <% loop Suburbs %>
                                            <% if $hasStockists %>
                                        <option value="$Name" data-type="suburb" data-city="$Up.Name">$Name</option>
                                            <% end_if %>
                                        <% end_loop %>
                                    <% else %>
                                    <option value="$Name" data-type="city" data-city="$Name">$Name</option>
                                    <% end_if %>
                                    </optgroup>
                                        <% end_if %>
                                    <% end_loop %>
                                <% end_if %>

                            <% end_loop %>
                            </select>
                        </form>
                    </div>
                </header>
                <div id="map"></div>
                <footer class="container"></footer>
            </div>
        </section>
