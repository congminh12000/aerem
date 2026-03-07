// Register elements.
import 'vidstack/player';
import 'vidstack/player/ui';
import 'vidstack/icons';
import { TextTrack } from 'vidstack';


/* adding in chapters */

document.addEventListener("DOMContentLoaded",() => {

    const mediaPlayers = document.querySelectorAll('.brxe-xmediaplayer');

    mediaPlayers.forEach(player => {

        const configAttr = player.getAttribute('data-x-media-player')
        const config = configAttr ? JSON.parse(configAttr) : {}

        if (!config.playlist) {

            player.addEventListener("x_media_player:ready", () => {

                if ( player.hasAttribute('data-x-chapters') ) {

                    const track = new TextTrack({
                        label: 'English',
                        language: 'en-US',
                        kind: 'chapters',
                        default: true,
                        type: 'json'
                    });

                    const chaptersAttr = player.getAttribute('data-x-chapters')
                    const chapters = chaptersAttr ? JSON.parse(chaptersAttr) : {} 

                    /* autofill last chapter end time if left blank
                    if (0 === chapters[chapters.length - 1].endTime) {
                        chapters[chapters.length - 1].endTime = e.detail.duration
                    }

                     */

                    for (const cue of chapters) {
                        track.addCue(new window.VTTCue(cue.startTime, cue.endTime, cue.text));
                    }

                    player.textTracks.add(track);

                }

            })

        } else {

            player.addEventListener('x_media_player:switch', (e) => {

                let chapters = e.detail.chapters;

                const newTrack = new TextTrack({
                    label: 'English',
                    language: 'en-US',
                    kind: 'chapters',
                    default: true,
                    type: 'json'
                });

                for (const cue of chapters) {
                    newTrack.addCue(new window.VTTCue(cue.startTime, cue.endTime, cue.text));
                }

                player.textTracks.clear();
                player.textTracks.add(newTrack);

            })

        }

    })

})

