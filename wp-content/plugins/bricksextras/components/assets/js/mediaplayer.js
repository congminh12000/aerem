function xDoMediaPlayer() {

    /* play list media switching */
    function selectPlaylistItemLoad(playListItem, player, config) {

        if (!playListItem) { return }
        if (!playListItem.getAttribute('data-x-src')) { return }
        
        playListItem.setAttribute('data-x-item-active','')
        playListItem.dispatchEvent(new Event('x_media_playlist:active'))

        if (playListItem.hasAttribute('role')) {
            playListItem.setAttribute('aria-pressed', 'true')
        }
    
        

        /* local poster  */

        const posterInstance = player.querySelector("media-poster");

        if ( posterInstance ) {

            if ( !playListItem.hasAttribute('data-x-local-poster') ) {

                    if (playListItem.getAttribute('data-x-src')) {

                        player.addEventListener('can-play', () => {

                            posterInstance.subscribe(({ src }) => {
                                if (src) {

                                    var xhr = new XMLHttpRequest();
                                    xhr.open("POST", xMediaPlayer.ajaxurl, true);
                                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                                    var postData = "action=check_poster&video_src=" + playListItem.getAttribute('data-x-src') + "&poster_src=" + posterInstance.querySelector('img').src;
                                    xhr.send(postData);


                                }
                            });

                        })

                    }

            } else {

                if ( !playListItem.hasAttribute('data-x-poster') ) {
                    player.querySelector('media-poster').src = playListItem.getAttribute('data-x-local-poster');
                    
                    setTimeout(() => {
                        player.querySelector('media-poster img').dataset.src = playListItem.getAttribute('data-x-local-poster');
                    }, "0");

                } else {
                    player.querySelector('media-poster').src = playListItem.getAttribute('data-x-poster');
                    
                    setTimeout(() => {
                        player.querySelector('media-poster img').dataset.src = playListItem.getAttribute('data-x-poster');
                    }, "0");
                    
                }

                

            }

        } 

        

        /* clear tracks */
        player.textTracks.clear();

        setTimeout(() => {
            if ( playListItem.getAttribute('data-x-chapters') ) {
                
                const chaptersAttr = playListItem.getAttribute('data-x-chapters')
                const chapters = chaptersAttr ? JSON.parse(chaptersAttr) : {} 

                player.dispatchEvent(new CustomEvent("x_media_player:switch", {
                    detail: { chapters: chapters }
                }));

            }
          }, "0"); 


           /* thumbnails */

           if ( player.querySelector("media-thumbnail") ) {
            player.querySelector("media-thumbnail").src = ''
            if ( playListItem.getAttribute('data-x-thumbnails') ) {
                player.querySelector("media-thumbnail").src = playListItem.getAttribute('data-x-thumbnails')
            }
           }

           if ( player.querySelector("media-slider-thumbnail") ) {
            player.querySelector("media-slider-thumbnail").src = ''
            if ( playListItem.getAttribute('data-x-thumbnails') ) {
                player.querySelector("media-slider-thumbnail").src = playListItem.getAttribute('data-x-thumbnails')
            }
           }

           



        /* reset clipping */
        player.clipStartTime = 0
        player.clipEndTime = 0

        /* change clipping */
        if ( playListItem.getAttribute('data-x-clip-start') ) {
            player.clipStartTime = playListItem.getAttribute('data-x-clip-start')
        }

        if ( playListItem.getAttribute('data-x-clip-end') ) {
            player.clipEndTime = playListItem.getAttribute('data-x-clip-end')
        }

        if (playListItem.getAttribute('data-x-title')) {
            player.setAttribute('title',playListItem.getAttribute('data-x-title')) 
        }

        if (playListItem.getAttribute('data-x-texttracks')) {

            const texttracksAttr = playListItem.getAttribute('data-x-texttracks')
            const texttracks = texttracksAttr ? JSON.parse(texttracksAttr) : {}

            if ( Array.isArray(texttracks) ) {
            
                    texttracks.forEach((track,index) => {

                    var trackEl = document.createElement("track");
                    trackEl.setAttribute('label',track.label)
                    trackEl.setAttribute('src',track.src)
                    trackEl.setAttribute('kind',track.kind)
                    trackEl.setAttribute('scslang',track.language)
                    if (0 === index) {
                        trackEl.setAttribute('default','')
                    }

                    player.querySelector('media-provider').appendChild(trackEl);

                })

            }

        }
         

    }

    function pauseOutOfView(player) {

        const onIntersection = (entries,observer) => {
            for (const entry of entries) {
                if (!entry.isIntersecting) {
                    player.pause()
                    observer.unobserve(player)
                }
            }
        };

        const observer = new IntersectionObserver(onIntersection,{
            root: null,
            rootMargin: '0px 0px 200px 0px',
            threshold: 0
        });

        observer.observe(player); 
    }


    function lazyLoadPoster(player, config) {
        const onIntersection = (entries,observer) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {

                    
                    if ( player.querySelector('media-poster img') && player.querySelector('media-poster img').dataset.src ) {

                        player.querySelector('media-poster').src = player.querySelector('media-poster img').dataset.src
                        
                        setTimeout(() => {
                            player.querySelector('media-poster img').src = player.querySelector('media-poster img').dataset.src
                            player.querySelector('media-poster').setAttribute('data-visible','')
                            player.querySelector('media-poster img').style.removeProperty('display')
                        }, "0");

                    } 

                    
                    
                    else if ( config.poster && player.querySelector('media-poster img') ) {
                        if ( document.querySelector('body > .brx-body.iframe') ) {
                           player.querySelector('media-poster').src = config.poster
                        } else {
                            player.querySelector('media-poster img').src = config.poster
                            player.querySelector('media-poster').removeAttribute('data-hidden')
                            player.querySelector('media-poster').setAttribute('data-visible','')
                            player.querySelector('media-poster img').style.removeProperty('display')
                        }
                            
                    } 
                    
                    else {
                        if (!config.playlist && !config.autoLocalPoster) {
                          player.startLoadingPoster()
                        }
                    }

                    if ( player.querySelector('media-poster img') && player.querySelector('media-poster').hasAttribute('alt') ) {
                        //player.querySelector('media-poster img').setAttribute('alt', player.querySelector('media-poster').getAttribute('alt') )
                    }
                    

                    observer.unobserve(player)
                }
            }
        };

        const observer = new IntersectionObserver(onIntersection,{
            root: null,
            rootMargin: '0px 0px 200px 0px',
            threshold: 0
        });
        observer.observe(player);  
    }

    function loadOnClick(player) {
        player.querySelectorAll('.vds-button').forEach(button => {
            button.style.removeProperty('pointer-events');
            //controlGroup.style.opacity = '0.7';
        })
        player.querySelectorAll('.vds-slider').forEach(controlSlider => {
            controlSlider.style.pointerEvents = "none"
        })

        player.addEventListener('click', startPlayer)
        player.querySelectorAll('media-play-button').forEach(play => {
            play.addEventListener('keydown', startPlayer)
        })
        

        function startPlayer(e) {

            if ( null == e.code || ( e.target.closest('media-play-button') && ( e.code === "Enter" || e.code === "Space" ) ) ) {
                player.startLoading()
            }
            
            player.addEventListener('can-play', () => {
                
                player.querySelectorAll('.vds-button').forEach(button => {
                    button.style.pointerEvents = 'auto';
                    button.style.removeProperty('opacity');
                })
                player.querySelectorAll('.vds-slider').forEach(controlSlider => {
                    controlSlider.style.removeProperty('pointer-events');
                })

                player.removeEventListener('click', startPlayer)
                player.querySelectorAll('media-play-button').forEach(play => {
                    play.removeEventListener('keydown', startPlayer)
                })
            })
        }
    }

    function autoPause(mediaPlayers,player) {
        player.addEventListener('playing', () => {
            mediaPlayers.forEach(otherPlayer => {
                if (otherPlayer !== player) {
                    if ( window.xMediaPlayer.Instances[otherPlayer.closest('.brxe-xmediaplayer').getAttribute('data-x-id')] ) {
                        window.xMediaPlayer.Instances[otherPlayer.closest('.brxe-xmediaplayer').getAttribute('data-x-id')].pause()
                    }
                }
            })
        })
    }

    /* moving between media */
    function switchMediaSrc(e, player, containerEL, config, autoplay = true) {

        if ( ! e.target.closest('.brxe-xmediaplaylist') ) {
            return;
        }

        let playListItem = e.target.closest('.brxe-xmediaplaylist');
        let newSrc = playListItem.getAttribute('data-x-src');
        let newTitle = playListItem.getAttribute('data-x-title') ? playListItem.getAttribute('data-x-title') : '';
        if (!newSrc) {return}

        let link = false;

        if ( e.target.closest('a') ) {
            e.preventDefault();
            link = true
        }

        /* */

        player.setAttribute('data-x-played-playlist','')

       
        player.clipStartTime = 0
        player.clipEndTime = 0

        
        if ( playListItem.getAttribute('data-x-clip-start') ) {
            player.clipStartTime = playListItem.getAttribute('data-x-clip-start')
        }

        if ( playListItem.getAttribute('data-x-clip-end') ) {
            player.clipEndTime = playListItem.getAttribute('data-x-clip-end')
        }

        if (playListItem.getAttribute('data-x-title')) {
            player.setAttribute('title',playListItem.getAttribute('data-x-title')) 
        }

        player.querySelectorAll('media-provider track').forEach(track => {
            track.remove()
        })

       
        if (playListItem.getAttribute('data-x-texttracks')) {

            const texttracksAttr = playListItem.getAttribute('data-x-texttracks')
            const texttracks = texttracksAttr ? JSON.parse(texttracksAttr) : {}

            
            if ( Array.isArray(texttracks) ) {
            
                    texttracks.forEach((track,index) => {

                    let trackEl = document.createElement("track");
                        trackEl.setAttribute('label',track.label)
                        trackEl.setAttribute('src',track.src)
                        trackEl.setAttribute('kind',track.kind)
                        trackEl.setAttribute('scslang',track.language)
                        if (0 === index) {  trackEl.setAttribute('default','') }

                    player.querySelector('media-provider').appendChild(trackEl);

                })

            }

        }

        
        
        
        player.textTracks.clear();

        
         if ( playListItem.getAttribute('data-x-chapters') ) {

            setTimeout(() => {
                
                const chaptersAttr = playListItem.getAttribute('data-x-chapters')
                const chapters = chaptersAttr ? JSON.parse(chaptersAttr) : {} 

                player.dispatchEvent(new CustomEvent("x_media_player:switch", {
                    detail: { chapters: chapters }
                }));

            }, "0");

        }

        

        player.clipStartTime = 0
        player.clipEndTime = 0

       
        if ( playListItem.getAttribute('data-x-clip-start') ) {
            player.clipStartTime = parseInt ( playListItem.getAttribute('data-x-clip-start') )
        }

        if ( playListItem.getAttribute('data-x-clip-end') ) {
            player.clipEndTime = parseInt ( playListItem.getAttribute('data-x-clip-end') )
        }
       
        if ( player.querySelector('.vds-time-slider') ) {
            player.querySelector('.vds-time-slider').style.removeProperty('pointer-events');
        }
        

        player.querySelectorAll('.vds-button').forEach(button => {
            button.style.pointerEvents = 'auto';
            button.style.removeProperty('opacity');
        })
        
        containerEL.querySelectorAll('.brxe-xmediaplaylist').forEach((item) => {

            if ( item.hasAttribute('data-x-item-active') ) {
                item.removeAttribute('data-x-item-active')
                item.dispatchEvent(new Event('x_media_playlist:inactive'))
            }
            
            item.removeAttribute('data-x-item-playing')
            item.removeAttribute('data-x-item-paused')
            if (!link) {
                item.setAttribute('aria-pressed', 'false')
            }
        })

        player.addEventListener('can-play', () => {

            setTimeout(() => {
                if (config.playlistPlayOnClick) {
                    player.paused = false
                }   
            }, 10);

        });

        

        playListItem.setAttribute('data-x-item-active','')
        playListItem.dispatchEvent(new Event('x_media_playlist:active'))

        if (!link) {
            playListItem.setAttribute('aria-pressed', 'true')
        }

        

        

        //player.startLoading();
        //player.startLoadingPoster();

        

         /* swap poster image */

        player.querySelector('media-poster').src = ''

         if ( player.querySelector('media-poster img') ) {
            player.querySelector('media-poster img').src = ''
            player.querySelector('media-poster img').removeAttribute('alt')
            player.querySelector('media-poster img').dataset.src = ''
            player.querySelector('media-poster').src = playListItem.getAttribute('data-x-poster') ? playListItem.getAttribute('data-x-poster') : '';
        }


        
        
        

        player.src = newSrc;
        player.title = newTitle;

        /* thumbnails */

        setTimeout(() => {

            if ( player.querySelector("media-thumbnail") ) {
                //player.querySelector("media-thumbnail").src = ''
                if ( playListItem.getAttribute('data-x-thumbnails') ) {
                    player.querySelectorAll("media-thumbnail").forEach(mediaThumbnail => {
                        mediaThumbnail.src = playListItem.getAttribute('data-x-thumbnails')
                    })
                }
                }
        
                if ( player.querySelector("media-slider-thumbnail") ) {
                player.querySelector("media-slider-thumbnail").src = ''
                if ( playListItem.getAttribute('data-x-thumbnails') ) {
                    player.querySelectorAll("media-slider-thumbnail").forEach(mediaSliderThumbnail => {
                        mediaSliderThumbnail.src = playListItem.getAttribute('data-x-thumbnails')
                    })
                }
            } 

        }, 0);

        player.startLoadingPoster();
    
         

    }

    

    const extrasMediaPlayer = function ( container, initPlayer = true ) {


        const mediaPlayers = container.querySelectorAll('.brxe-xmediaplayer');
        let playlistItems
         

        mediaPlayers.forEach(player => {

            const configAttr = player.getAttribute('data-x-media-player')
            const config = configAttr ? JSON.parse(configAttr) : {}


                let containerEL = null != config.isLooping ? player.closest('.brxe-' + config.isLooping) : player.closest('.brxe-section')

                if (  player.closest('.ginner-container') ) {
                    containerEL = player.closest('.ginner-container');
                }

                if (player.querySelector('.vds-controls')) {
                    player.querySelector('.vds-controls').style.removeProperty('visibility')
                }
                

                if ( 'custom' === config.load ) {
                     loadOnClick(player)
                }

                player.addEventListener('provider-change', (event) => {
                    const provider = event.detail;
                    if (provider?.type === 'hls') {
                      provider.library = xMediaPlayer.pluginDir + 'hls.js';
                    }
                  });
                
                player.addEventListener('can-play', () => {

                    window.xMediaPlayer.Instances[player.dataset.xId] = player;
                    player.dispatchEvent(new Event('x_media_player:ready'))

                    player.addEventListener('started', () => {
                        player.setAttribute('data-x-played', 'true');
                    })

                });

                if ( null != config.breakpoint && !document.querySelector('body > .brx-body.iframe') ) {

                    player.subscribe(({ width }) => {

                        if ( width >= config.breakpoint ) {
                            if ( player.querySelector('.media-layout_large-video > *') ) {
                                player.querySelector('.media-layout_large-video > *').style.removeProperty('display')
                            }
                            if ( player.querySelector('.media-layout_small-video > *') ) {
                                player.querySelector('.media-layout_small-video > *').style.display = 'none'
                            }
                        } else {
                            if ( player.querySelector('.media-layout_large-video > *') ) {
                                player.querySelector('.media-layout_large-video > *').style.display = 'none'
                            }
                            if ( player.querySelector('.media-layout_small-video > *') ) {
                                player.querySelector('.media-layout_small-video > *').style.removeProperty('display')
                            }
                        }

                    });

                }

                
                /* local poster, if not playlist  */

                const posterInstance = player.querySelector("media-poster");

                if (!config.playlist && config.autoLocalPoster && posterInstance) {

                    if ( !player.hasAttribute('data-x-local-poster') ) {

                        posterInstance.subscribe(({ src }) => {

                            if (src) {

                                var xhr = new XMLHttpRequest();
                                xhr.open("POST", xMediaPlayer.ajaxurl, true);
                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                                var postData = "action=check_poster&video_src=" + player.src + "&poster_src=" + src;
                                xhr.send(postData);

                            }
                        });

                    } else {
                        if (!config.poster) {
                            posterInstance.querySelector('img').dataset.src = player.getAttribute('data-x-local-poster')
                        } else {
                            posterInstance.querySelector('img').dataset.src = config.poster
                        }
                        posterInstance.removeAttribute('data-hidden')
                    }

                } else {
                    if (config.poster && posterInstance) {
                        posterInstance.querySelector('img').dataset.src = player.getAttribute('data-x-local-poster')
                    }
                    //player.startLoadingPoster()

                }

                /* autopause */
                autoPause(mediaPlayers,player)

                if (config.pauseOutOfView) {
                    player.addEventListener('playing', () => {
                        pauseOutOfView(player)
                    })
                }

                let count = 0

                let watchTimer = new xMediaTimer(function () {
                    count = ++count;
                    
                    player.dispatchEvent(new CustomEvent('x_media_player:watching', {
                        detail: {
                            count: count
                        }
                      }));
                  }, 1000);


                player.addEventListener('can-play', () => {
                    player.addEventListener('playing', watchTimer.resume)
                    player.addEventListener('pause', watchTimer.pause)
                    player.addEventListener('seeking', watchTimer.pause)

                    if ( document.querySelector('body > .brx-body.iframe') ) {
                        if (config.poster && player.querySelector('media-poster img') ) {
                            player.querySelector('media-poster img').src = config.poster
                        }
                    }
                })



                 /* playlist mode */

                if (config.playlist && containerEL) {

                    playlistItems = containerEL.querySelectorAll('.brxe-xmediaplaylist');
                    
                    
                    selectPlaylistItemLoad(playlistItems[0],player);

                    
                    containerEL.addEventListener('click', (e) => switchMediaSrc(e, player, containerEL, config, false ));
                    containerEL.addEventListener('keypress', (e) => switchMediaSrc(e, player, containerEL, config, false ));

                    playlistItems.forEach(item => {
                        if (!item.querySelector('a')) {
                            item.setAttribute('tabindex','0')
                            item.setAttribute('role','button')
                        }
                    })
                   

                    
                    if (config.playListNext) {
                        if ( player.querySelector('media-poster') ) {
                            player.addEventListener('playing', () => {
                                player.querySelector('media-poster').style.visibility = 'hidden'
                            })
                        }

                        player.addEventListener('ended', () => {

                            let nextItem = [];
                            let playlistItems = containerEL.querySelectorAll('.brxe-xmediaplaylist')

                            playlistItems.forEach((playlistItem, index) => {

                                if ( playlistItem.hasAttribute('data-x-item-active') ) {
                                    if (index < playlistItems.length - 1) {  
                                        nextItem.push(playlistItems[index + 1]) 
                                     }
                                    if (config.playListLoop && ( index === playlistItems.length - 1)){ 
                                        nextItem.push(playlistItems[0]);
                                    }
                                }

                            })

                            if (nextItem.length !== 0) {
                                setTimeout(() => {
                                    nextItem[0].click()
                                }, config.playListDelay);
                            }

                        })

                    }

                    

                    player.subscribe(({ paused }) => {

                        let playlistItems = containerEL.querySelectorAll('.brxe-xmediaplaylist')

                            playlistItems.forEach((playlistItem) => {

                                if ( playlistItem.hasAttribute('data-x-item-active') ) {

                                if (paused) {
                                    playlistItem.dispatchEvent(new Event('x_media_playlist:paused'))
                                    playlistItem.setAttribute('data-x-item-paused','')
                                    playlistItem.removeAttribute('data-x-item-playing')
                                } else {
                                    playlistItem.dispatchEvent(new Event('x_media_playlist:playing'))
                                    playlistItem.setAttribute('data-x-item-playing','')
                                    playlistItem.removeAttribute('data-x-item-paused')
                                }

                                }

                            })

                    });

                }

                

                lazyLoadPoster(player, config);

            

        })

    }

    extrasMediaPlayer(document);

    function xMediaPlayerAJAX(e) {

        if (typeof e.detail.queryId === 'undefined') {
            if ( typeof e.detail.popupElement === 'undefined' ) {
                return;
            } else {
                extrasMediaPlayer( e.detail.popupElement )
            }
        }

        setTimeout(() => {
            if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
                extrasMediaPlayer(document.querySelector('.brxe-' + e.detail.queryId).parentElement);
            }
        }, 0);
      }
      
      document.addEventListener("bricks/ajax/load_page/completed", xMediaPlayerAJAX)
      document.addEventListener("bricks/ajax/pagination/completed", xMediaPlayerAJAX)
      document.addEventListener("bricks/ajax/popup/loaded", xMediaPlayerAJAX)
      document.addEventListener("bricks/ajax/end", xMediaPlayerAJAX)

    // Expose function
    window.doExtrasMediaPlayer = extrasMediaPlayer;

}

function xMediaTimer(callback, delay) {
    var timerId;
    var start;
    var remaining = delay;
    
    this.pause = function () {
        window.clearTimeout(timerId);
        remaining -= new Date() - start;
    };
    
    var resume = function () {
        start = new Date();
        timerId = window.setTimeout(function () {
        remaining = delay;
        resume();
        callback();
        }, remaining);
    };
    this.resume = resume;
}

document.addEventListener("DOMContentLoaded",function(e){
    bricksIsFrontend&&xDoMediaPlayer()

    if (typeof bricksextras !== 'undefined') {

        bricksextras.mediaplayer = {
          play: (brxParam) => {
            let target = brxParam?.target || false
            if ( target ) {
                target.paused = false
            }
          },
          pause: (brxParam) => {
            let target = brxParam?.target || false
            if ( target ) {
                target.paused = true
            }
          },
          toggleplay: (brxParam) => {
            let target = brxParam?.target || false
            if ( target ) {
                target.paused = !target.paused
            }
          },
          togglemute: (brxParam) => {
            let target = brxParam?.target || false
            if ( target ) {
                target.muted = !target.muted
            }
          },
          enterfullscreen: (brxParam) => {
            let target = brxParam?.target || false
            if ( target ) {
                target.enterFullscreen()
            }
          },
          exitfullscreen: (brxParam) => {
            let target = brxParam?.target || false
            if ( target ) {
                target.exitFullscreen()
            }
          },
        }
      
      }

 });
