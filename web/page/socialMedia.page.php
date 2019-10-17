<?php if ( isset($response) && $response != '' )
    // If user not loged is return access token here
    echo "ACCESS TOKEN: " . $response;
?>
<?php require(HEAD); ?>

    <section class="row section-1 bg-white">

        <ul class="tabs" data-tabs="30resy-tabs" id="socialMedia-tabs" role="tablist">
            <li class="tabs-title is-active" role="presentation"><a href="#panel1" aria-selected="true" role="tab" aria-controls="panel1" id="panel1-label">Instagram Feed</a></li>
            <li class="tabs-title" role="presentation"><a href="#panel2" role="tab" aria-controls="panel2" aria-selected="false" id="panel2-label">Twitter Feed</a></li>
            <li class="tabs-title" role="presentation"><a href="#panel3" role="tab" aria-controls="panel3" aria-selected="false" id="panel3-label">Facebook Feed</a></li>
        </ul>

        <div class="tabs-content" data-tabs-content="socialMedia-tabs">

          <div class="tabs-panel is-active" id="panel1" role="tabpanel" aria-hidden="false" aria-labelledby="panel1-label">
              <ul class="instagram_feed">
              	<?php foreach ($instagramFeed->data as $post): ?>
              		<li class="columns column-block small-6 medium-4">
                        <span class="likes-count"> <i class="icons icons-heart xxsmall"></i> <span class="icon-label">Likes:</span> <?= $post->likes->count ?></span><span class="comments-count"><i class="icons icons-bubble2 xxsmall"></i> <span class="icon-label">Comments:</span> <?=  $post->comments->count ?></span> <br / />
              			<a href="<?= $post->images->thumbnail->url ?>"><img src="<?= $post->images->thumbnail->url ?>" width="<?= $post->images->thumbnail->width ?>" height="<?= $post->images->thumbnail->height ?>" /></a>
              			<p>
              				<strong>Caption:</strong> <?php if( isset($post->caption->text) ) { echo $post->caption->text; }  ?><br />
              				<span><strong>User Name:</strong> <?= $post->user->username ?></span>
              				<span><strong>Link:</strong> <a href="<?= $post->link ?>" target="_blank">visit full image</a></span>
              			</p>
              		</li>
              	<?php endforeach; ?>
              </ul>
              <?php /* NOTE if we parse a count value in the controller this logic will be fired it will load the next count value of images from users feed */ ?>
              <?php if( isset($instagramFeed->pagination->next_url) ): ?>
                  <a href="#" class="btn_nextLink" data-link="<?= $instagramFeed->pagination->next_url ?>" data-next-count="<?= $NEXTCOUNT?>" >Load More</a>
              <?php endif; ?>
          </div>

          <div class="tabs-panel" id="panel2" role="tabpanel" aria-hidden="true" aria-labelledby="panel2-label">
              <div class="twitter_wrapper">

                  <?php /* START Profile Information */  ?>
                  <a href="https://twitter.com/<?= $twitterFeed[0]['user']['screen_name']; ?>" target="_blank">
                      <img src="<?= $twitterFeed[0]['user']['profile_image_url_https']; ?>" alt="<?= $twitterFeed[0]['user']['screen_name']; ?>" title="<?= $twitterFeed[0]['user']['screen_name']; ?>" />
                  </a>
                  <a href="https://twitter.com/<?= $twitterFeed[0]['user']['screen_name']; ?>" target="_blank">@<?= $twitterFeed[0]['user']['screen_name']; ?>'s Twitter Feed</a>

                  <p>
                      <span><strong>Tweets: </strong><?=$twitterFeed[0]['user']['statuses_count']; ?></span>
                      <span><strong>Followers: </strong><?=$twitterFeed[0]['user']['followers_count']; ?></span>
                  </p>

                  <p>
                      <strong>Name: </strong><?= $twitterFeed[0]['user']['name']; ?>
                  </p>
                  <p>
                      <strong>Location: </strong><?= $twitterFeed[0]['user']['location']; ?>
                  </p>
                  <p>
                      <strong>Bio: </strong><?= $twitterFeed[0]['user']['description']; ?>
                  </p>
                  <p>
                      <strong>URL: </strong><a href="<?= $twitterFeed[0]['user']['entities']['url']['urls'][0]['expanded_url']; ?>" title="<?= $twitterFeed[0]['user']['screen_name']; ?> on twitter"> <?= $twitterFeed[0]['user']['entities']['url']['urls'][0]['display_url']; ?> </a>
                  </p>
                  <p>
                      <strong>Latest Tweets:</strong>
                  </p>
                  <?php /* END Profile Information */  ?>

                  <?php /* START Twitter Feed */  ?>
                  <ul class="twitter_feed">

                      <?php foreach ($twitterFeed as $tweet): ?>

                          <?php
                          /* TODO: Move this logic inside the social media class. json returned should contain only
                            The information our feed requires pre formatted in the way we expect.
                          */
                              // get tweet text
                              $tweet_text=$tweet['text'];
                              // make links clickable
                              $tweet_text=preg_replace('@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $tweet_text);

                              // format created_at date / time
                              $date = new DateTime($tweet['created_at']);
                              $formatted_date = $date->format('d M Y');
                          ?>

                          <li class="tweet">

                              <a href="https://twitter.com/<?= $tweet['user']['screen_name']; ?>" target="_blank">
                                  <img src="<?= $tweet['user']['profile_image_url_https']; ?>" alt="<?= $tweet['user']['screen_name']; ?>" title="<?= $tweet['user']['screen_name']; ?>" />
                              </a>

                              <span>
                                  <?= $tweet_text; ?> <br />
                                  Posted at: <?= $formatted_date ?> Replys: Retweets:<?= $tweet['retweet_count']; ?> Favourited: <?= $tweet['favorite_count']; ?>
                              </span>
                          </li>
                      <?php endforeach; ?>

                  </ul>
                  <?php /* END Twitter Feed */  ?>

              </div>
          </div>

          <div class="tabs-panel" id="panel3" role="tabpanel" aria-hidden="true" aria-labelledby="panel3-label">

              <?php foreach ($faceboookFeed as $facebookStatus): ?>
              <li class="row column bg-white">

                <?php if ( isset( $facebookStatus['picture'] )): ?>
                  <img src="<?=$facebookStatus['picture'];?>" alt="photo" />
                <?php endif; ?>

                <?= $facebookStatus['name']; ?> -
                <?= $facebookStatus['created_at'] ?> <br />

                <?php if ( isset( $facebookStatus['message'] ) ): ?>
                  <p>
                    <?=$facebookStatus['message'];?>
                  </p>
                <?php endif; ?>

                <?php if ( isset( $facebookStatus['story'] ) ): ?>
                  <p>
                    <?=$facebookStatus['story'];?>
                  </p>
                <?php endif; ?>


                <?php if ( $facebookStatus['has-subattachment'] == false ): ?>

                  <?php if ( isset( $facebookStatus['attachment-img'] ) ): ?>

                    <?php if ( $facebookStatus['is-story'] == false ):  ?>

                      <a href="<?=$facebookStatus['action-link'];?>" title="<?=$facebookStatus['message'];?>" target="_blank">
                      <img src="<?=$facebookStatus['attachment-img'];?>" alt="<?=$facebookStatus['message'];?>" />

                    <?php else: ?>

                      <a href="<?=$facebookStatus['action-link'];?>" title="<?=$facebookStatus['story'];?>" target="_blank">
                      <img src="<?=$facebookStatus['attachment-img'];?>" alt="<?=$facebookStatus['story'];?>" />

                    <?php endif; ?>
                    </a>
                  <?php endif; ?>

                <?php else: ?>

                  <div class="row">
                    <?php foreach ($facebookStatus['sub-attachment'] as $subattachment): ?>
                      <div class="columns small-12 medium-12 large-6">
                        <a href="" title="<?=$subattachment['description'];?>" target="_blank">
                          <img src="<?=$subattachment['img'];?>" alt="<?=$subattachment['description'];?>" />
                          <caption><?=$subattachment['description'];?></caption>
                        </a>
                      </div>
                    <?php endforeach; ?>
                  </div>

                <?php endif; ?>

                <!-- START POST ACTIONS -->
                  <ul>
                    <li><a href="<?=$facebookStatus['action-link'];?>" title="Like" target="_blank">Like (<?=$facebookStatus['actions_likes'];?>)</a></li>
                    <li><a href="<?=$facebookStatus['action-link'];?>" title="Comment" target="_blank">Comment (<?=$facebookStatus['actions_comments'];?>)</a></li>
                    <li><a href="<?=$facebookStatus['action-link'];?>" title="Share" target="_blank">Share (<?=$facebookStatus['actions_shares'];?>)</a></li>
                  </ul>
                <!-- END POST ACTIONS -->

                <?php if ( isset( $facebookStatus['action-link'] ) ): ?>
                  <br /><br />
                  <a href="<?=$facebookStatus['action-link'];?>" target="_blank" title="Visit post on facebook">Visit Post on Facebook</a>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>

          </div>

        </div>

    </section>

    <!-- content end -->
<?php require(FOOT); ?>
