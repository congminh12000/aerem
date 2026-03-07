<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class X_Event_Calendar extends \Bricks\Element {

  // Element properties
  public $category     = 'extras';
	public $name         = 'xeventcalendar';
	public $icon         = 'ti-calendar';
	public $css_selector = '';
	public $scripts      = ['xEventCalendar'];
  public $loop_index = 0;

  
  public function get_label() {
	return esc_html__( 'Calendar', 'extras' );
  }
  public function set_control_groups() {

    $this->control_groups['data_group'] = [
			'title' => esc_html__( 'Dynamic Data', 'extras' ),
			'tab'   => 'content',
		];

    $this->control_groups['views_group'] = [
			'title' => esc_html__( 'Views', 'extras' ),
			'tab'   => 'content',
		];

    $this->control_groups['style_group'] = [
			'title' => esc_html__( 'Style Calendar', 'extras' ),
			'tab'   => 'content',
		];

    $this->control_groups['click_group'] = [
			'title' => esc_html__( 'Click Action', 'extras' ),
			'tab'   => 'content',
		];

  }

  public function set_controls() {

    $this->controls = array_replace_recursive( $this->controls, $this->get_loop_builder_controls() );


    $this->controls['eventTitle'] = [
      'group' => 'data_group',
			'tab'  => 'content',
			'type' => 'text',
			'label' => esc_html__( 'Event title', 'bricks' ),
      'inline' => true,
      'default' => '{post_title}'
    ];

    $this->controls['eventDescription'] = [
      'group' => 'data_group',
			'tab'  => 'content',
			'type' => 'text',
			'label' => esc_html__( 'Event description', 'bricks' ),
      'inline' => true,
    ];

    $this->controls['eventStart'] = [
      'group' => 'data_group',
			'tab'  => 'content',
			'type' => 'text',
			'label' => esc_html__( 'Start date/time', 'bricks' ),
      'inline' => true,
      'default' => '{post_date}'
    ];

    $this->controls['eventEnd'] = [
      'group' => 'data_group',
			'tab'  => 'content',
			'type' => 'text',
			'label' => esc_html__( 'End date/time', 'bricks' ),
      'inline' => true
    ];

    $timezones = [
      "Pacific/Midway"=>"(GMT-11:00) Midway Island, Samoa",
      "America/Adak"=>"(GMT-10:00) Hawaii-Aleutian",
      "Etc/GMT+10"=>"(GMT-10:00) Hawaii",
      "Pacific/Marquesas"=>"(GMT-09:30) Marquesas Islands",
      "Pacific/Gambier"=>"(GMT-09:00) Gambier Islands",
      "America/Anchorage"=>"(GMT-09:00) Alaska",
      "America/Ensenada"=>"(GMT-08:00) Tijuana, Baja California",
      "Etc/GMT+8"=>"(GMT-08:00) Pitcairn Islands",
      "America/Los_Angeles"=>"(GMT-08:00) Pacific Time (US & Canada)",
      "America/Denver"=>"(GMT-07:00) Mountain Time (US & Canada)",
      "America/Chihuahua"=>"(GMT-07:00) Chihuahua, La Paz, Mazatlan",
      "America/Dawson_Creek"=>"(GMT-07:00) Arizona",
      "America/Belize"=>"(GMT-06:00) Saskatchewan, Central America",
      "America/Cancun"=>"(GMT-06:00) Guadalajara, Mexico City, Monterrey",
      "Chile/EasterIsland"=>"(GMT-06:00) Easter Island",
      "America/Chicago"=>"(GMT-06:00) Central Time (US & Canada)",
      "America/New_York"=>"(GMT-05:00) Eastern Time (US & Canada)",
      "America/Havana"=>"(GMT-05:00) Cuba",
      "America/Bogota"=>"(GMT-05:00) Bogota, Lima, Quito, Rio Branco",
      "America/Caracas"=>"(GMT-04:30) Caracas",
      "America/Santiago"=>"(GMT-04:00) Santiago",
      "America/La_Paz"=>"(GMT-04:00) La Paz",
      "Atlantic/Stanley"=>"(GMT-04:00) Faukland Islands",
      "America/Campo_Grande"=>"(GMT-04:00) Brazil",
      "America/Goose_Bay"=>"(GMT-04:00) Atlantic Time (Goose Bay)",
      "America/Glace_Bay"=>"(GMT-04:00) Atlantic Time (Canada)",
      "America/St_Johns"=>"(GMT-03:30) Newfoundland",
      "America/Araguaina"=>"(GMT-03:00) UTC-3",
      "America/Montevideo"=>"(GMT-03:00) Montevideo",
      "America/Miquelon"=>"(GMT-03:00) Miquelon, St. Pierre",
      "America/Godthab"=>"(GMT-03:00) Greenland",
      "America/Argentina/Buenos_Aires"=>"(GMT-03:00) Buenos Aires",
      "America/Sao_Paulo"=>"(GMT-03:00) Brasilia",
      "America/Noronha"=>"(GMT-02:00) Mid-Atlantic",
      "Atlantic/Cape_Verde"=>"(GMT-01:00) Cape Verde Is.",
      "Atlantic/Azores"=>"(GMT-01:00) Azores",
      "Europe/Belfast"=>"(GMT) Greenwich Mean Time : Belfast",
      "Europe/Dublin"=>"(GMT) Greenwich Mean Time : Dublin",
      "Europe/Lisbon"=>"(GMT) Greenwich Mean Time : Lisbon",
      "Europe/London"=>"(GMT) Greenwich Mean Time : London",
      "Africa/Abidjan"=>"(GMT) Monrovia, Reykjavik",
      "Europe/Amsterdam"=>"(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
      "Europe/Belgrade"=>"(GMT+01:00)Belgrade, Bratislava, Budapest, Ljubljana, Prague",
      "Europe/Brussels"=>"(GMT+01:00) Brussels, Copenhagen, Madrid, Paris",
      "Africa/Algiers"=>"(GMT+01:00) West Central Africa",
      "Africa/Windhoek"=>"(GMT+01:00) Windhoek",
      "Asia/Beirut"=>"(GMT+02:00) Beirut",
      "Africa/Cairo"=>"(GMT+02:00) Cairo",
      "Asia/Gaza"=>"(GMT+02:00) Gaza",
      "Africa/Blantyre"=>"(GMT+02:00) Harare, Pretoria",
      "Asia/Jerusalem"=>"(GMT+02:00) Jerusalem",
      "Europe/Helsinki"=>"(GMT+02:00) Helsinki",
      "Europe/Minsk"=>"(GMT+02:00) Minsk",
      "Asia/Damascus"=>"(GMT+02:00) Syria",
      "Europe/Moscow"=>"(GMT+03:00) Moscow, St. Petersburg, Volgograd",
      "Africa/Addis_Ababa"=>"(GMT+03:00) Nairobi",
      "Asia/Tehran"=>"(GMT+03:30) Tehran",
      "Asia/Dubai"=>"(GMT+04:00) Abu Dhabi, Muscat",
      "Asia/Yerevan"=>"(GMT+04:00) Yerevan",
      "Asia/Kabul"=>"(GMT+04:30) Kabul",
      "Asia/Yekaterinburg"=>"(GMT+05:00) Ekaterinburg",
      "Asia/Tashkent"=>"(GMT+05:00) Tashkent",
      "Asia/Kolkata"=>"(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi",
      "Asia/Katmandu"=>"(GMT+05:45) Kathmandu",
      "Asia/Dhaka"=>"(GMT+06:00) Astana, Dhaka",
      "Asia/Novosibirsk"=>"(GMT+06:00) Novosibirsk",
      "Asia/Rangoon"=>"(GMT+06:30) Yangon (Rangoon)",
      "Asia/Bangkok"=>"(GMT+07:00) Bangkok, Hanoi, Jakarta",
      "Asia/Krasnoyarsk"=>"(GMT+07:00) Krasnoyarsk",
      "Asia/Hong_Kong"=>"(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi",
      "Asia/Irkutsk"=>"(GMT+08:00) Irkutsk, Ulaan Bataar",
      "Australia/Perth"=>"(GMT+08:00) Perth",
      "Australia/Eucla"=>"(GMT+08:45) Eucla",
      "Asia/Tokyo"=>"(GMT+09:00) Osaka, Sapporo, Tokyo",
      "Asia/Seoul"=>"(GMT+09:00) Seoul",
      "Asia/Yakutsk"=>"(GMT+09:00) Yakutsk",
      "Australia/Adelaide"=>"(GMT+09:30) Adelaide",
      "Australia/Darwin"=>"(GMT+09:30) Darwin",
      "Australia/Brisbane"=>"(GMT+10:00) Brisbane",
      "Australia/Hobart"=>"(GMT+10:00) Hobart",
      "Asia/Vladivostok"=>"(GMT+10:00) Vladivostok",
      "Australia/Lord_Howe"=>"(GMT+10:30) Lord Howe Island",
      "Etc/GMT-11"=>"(GMT+11:00) Solomon Is., New Caledonia",
      "Asia/Magadan"=>"(GMT+11:00) Magadan",
      "Pacific/Norfolk"=>"(GMT+11:30) Norfolk Island",
      "Asia/Anadyr"=>"(GMT+12:00) Anadyr, Kamchatka",
      "Pacific/Auckland"=>"(GMT+12:00) Auckland, Wellington",
      "Etc/GMT-12"=>"(GMT+12:00) Fiji, Kamchatka, Marshall Is.",
      "Pacific/Chatham"=>"(GMT+12:45) Chatham Islands",
      "Pacific/Tongatapu"=>"(GMT+13:00) Nuku'alofa",
      "Pacific/Kiritimati"=>"(GMT+14:00) Kiritimati",
    ];

    $this->controls['timeZone'] = [
      'tab'  => 'content',
			'type' => 'select',
			'label' => esc_html__( 'Time zone', 'bricks' ),
      'options' => $timezones,
      'searchable' => true,
      'group' => 'data_group',
      'inline' => true
    ];


    /*
    $this->controls['startRecur'] = [
      'group' => 'data_group',
			'tab'  => 'content',
			'type' => 'text',
			'label' => esc_html__( 'Recur start date/time', 'bricks' ),
      'inline' => true
    ];

    $this->controls['endRecur'] = [
      'group' => 'data_group',
			'tab'  => 'content',
			'type' => 'text',
			'label' => esc_html__( 'Recur end date/time', 'bricks' ),
      'inline' => true
    ];

    $daysOfWeek = [
      'sun' => 'Sunday',
      'mon' => 'Monday',
      'tue' => 'Tuesday',
      'wed' => 'Wednesday',
      'thu' => 'Thursday',
      'fri' => 'Friday',
      'sat' => 'Saturdary'
    ];

    $this->controls['daysOfWeek'] = [
      'tab'  => 'content',
			'type' => 'select',
			'label' => esc_html__( 'Days of Week', 'bricks' ),
      'options' => $daysOfWeek,
      'multiple'    => true,
      'group' => 'data_group',
      'inline' => true
    ];

    */
    

    $this->controls['eventURL'] = [
      'group' => 'data_group',
			'tab'  => 'content',
			'type' => 'text',
			'label' => esc_html__( 'Event URL', 'bricks' ),
      'inline' => true,
      'default' => '{post_url}'
    ];

    $this->controls['eventID'] = [
      'group' => 'data_group',
			'tab'  => 'content',
			'type' => 'text',
			'label' => esc_html__( 'Event ID', 'bricks' ),
      'inline' => true,
      'info' => esc_html__( 'Needs to be the {post_id) if using a lightbox', 'bricks' ),
      'default' => '{post_id}'
    ];

    $this->controls['eventColor'] = [
      'group' => 'data_group',
			'tab'  => 'content',
			'type' => 'text',
			'label' => esc_html__( 'Event Color', 'bricks' ),
      'inline' => true
    ];

    $availableViews = [
      'multiMonthYear' => 'multiMonthYear' ,
      'listWeek' => 'listWeek',
      'listMonth' => 'listMonth',
      'dayGridMonth' => 'dayGridMonth',
      'timeGridWeek' => 'timeGridWeek',
      'timeGridDay' => 'timeGridDay'
    ];

    /* Views */

    $this->controls['startView'] = [
			'tab'  => 'content',
			'type' => 'select',
			'label' => esc_html__( 'Start View', 'bricks' ),
      'options' => $availableViews,
      'group' => 'views_group',
      'inline' => true
    ];

    $this->controls['viewOptions'] = [
			'tab'  => 'content',
			'type' => 'select',
			'label' => esc_html__( 'Toolbar View Options', 'bricks' ),
      'options' => $availableViews,
      'multiple'    => true,
      'searchable' => true,
      'default'     => [
				'listMonth',
				'dayGridMonth',
        'timeGridDay'
			],
      'group' => 'views_group'
    ];




    /* styles 
    
        --fc-small-font-size: 0.85em;
    --fc-page-bg-color: #fff;
    
    --fc-neutral-text-color: grey;
    --fc-button-text-color: #fff;
    --fc-button-bg-color: #2c3e50;
    --fc-button-border-color: #2c3e50;
    --fc-button-hover-bg-color: #1e2b37;
    --fc-button-hover-border-color: #1a252f;
    --fc-button-active-bg-color: #1a252f;
    --fc-button-active-border-color: #151e27;
    --fc-event-bg-color: #3788d8;
    --fc-event-border-color: #3788d8;
    --fc-event-text-color: #fff;
    --fc-event-selected-overlay-color: rgba(0,0,0,0.25);
    --fc-more-link-bg-color: #d0d0d0;
    --fc-more-link-text-color: inherit;
    --fc-event-resizer-thickness: 8px;
    --fc-event-resizer-dot-total-width: 8px;
    --fc-event-resizer-dot-border-width: 1px;
    --fc-non-business-color: hsla(0,0%,84%,0.3);
    --fc-bg-event-color: #8fdf82;
    --fc-bg-event-opacity: 0.3;
    --fc-highlight-color: rgba(188,232,241,0.3);
    --fc-today-bg-color: rgba(255,220,40,0.15);
    --fc-now-indicator-color: red;
    
    
    */

    $this->controls['toolbarSep'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'separator',
      'label'  => esc_html__( 'Toolbar', 'extras' ),
    ];

    
    $this->controls['toolbarTypography'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'typography',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.fc .fc-toolbar',
        ],
      ],
    ];

    $this->controls['titleTypography'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'typography',
      'label'  => esc_html__( 'Title Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.fc .fc-toolbar-title',
        ],
      ],
    ];

    

    $this->controls['neutralBgColor'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'color',
      'label'  => esc_html__( 'Neutral background', 'extras' ),
      'css'    => [
        [
          'property' => '--fc-neutral-bg-color',
          'selector' => '.x-calendar',
        ],
      ],
      'placeholder' => [
        'hsl' => 'hsla(0, 0%, 82%, 0.3)'
      ]
    ];

    $this->controls['listDaySep'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'separator',
      'label'  => esc_html__( 'List Day', 'extras' ),
    ];

    $this->controls['listDayBg'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'color',
      'label'  => esc_html__( 'List Day background', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => '.fc-theme-standard .fc-list-day-cushion',
        ],
      ],
    ];

    $this->controls['listDayType'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'typography',
      'label'  => esc_html__( 'List Day Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.fc-theme-standard .fc-list-day-cushion',
        ],
      ],
    ];

    

    $this->controls['todayBgColor'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'color',
      'label'  => esc_html__( 'Today background', 'extras' ),
      'css'    => [
        [
          'property' => '--fc-today-bg-color',
          'selector' => '.x-calendar',
        ],
      ],
    ];

    
    

    $this->controls['calendarBorderColor'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'color',
      'label'  => esc_html__( 'Border color', 'extras' ),
      'css'    => [
        [
          'property' => '--fc-border-color',
          'selector' => '.x-calendar',
        ],
      ],
    ];

    $this->controls['eventsSep'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'separator',
      'label'  => esc_html__( 'Events', 'extras' ),
    ];
    

    $this->controls['eventBackground'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'color',
      'label'  => esc_html__( 'Background color', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => '.fc-event',
        ],

        
      ],
    ];

    $this->controls['eventBorderColor'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'color',
      'label'  => esc_html__( 'Border color', 'extras' ),
      'css'    => [
        [
          'property' => 'border-color',
          'selector' => '.fc-event',
        ],
      ],
    ];

    $this->controls['navSep'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'separator',
      'label'  => esc_html__( 'Navigation buttons', 'extras' ),
    ];

    $this->controls['navBackground'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'color',
      'label'  => esc_html__( 'Background color', 'extras' ),
      'css'    => [
        [
          'property' => 'background-color',
          'selector' => '.fc .fc-prev-button',
        ],
        [
          'property' => 'background-color',
          'selector' => '.fc .fc-next-button',
        ],
        
      ],
    ];

    $this->controls['navTypography'] = [
      'tab'    => 'content',
      'group'  => 'style_group',
      'type'   => 'typography',
      'label'  => esc_html__( 'Typography', 'extras' ),
      'css'    => [
        [
          'property' => 'font',
          'selector' => '.fc .fc-prev-button',
        ],
        [
          'property' => 'font',
          'selector' => '.fc .fc-next-button',
        ],
      ],
    ];


    /* click action */

    $this->controls['actionSep'] = [
      'tab'    => 'content',
      'group'  => 'click_group',
      'type'   => 'separator',
      'description'  => esc_html__( 'What action to take when the user clicks on any event in the calendar?', 'extras' ),
    ];
    
    $this->controls['clickAction'] = [
      'tab'  => 'content',
			'type' => 'select',
			'label' => esc_html__( 'Action', 'bricks' ),
      'options' => [
        'url' => esc_html__( 'Go to Event URL', 'bricks' ),
        'lightbox' => esc_html__( 'Open Dynamic Lightbox', 'bricks' ),
      ],
      'group' => 'click_group',
      'inline' => true,
      'placeholder' => esc_html__( 'Go to Event URL', 'bricks' ),
    ];

  }

  // Methods: Frontend-specific
  public function enqueue_scripts() {
    
    wp_enqueue_script( 'x-luxon', 'https://cdn.jsdelivr.net/npm/luxon@2.3.0/build/global/luxon.min.js', '', '1.0.0', true );
    wp_enqueue_script( 'x-event-calendar', BRICKSEXTRAS_URL . 'components/assets/js/eventcalendar.js', '', '1.0.0', true );

    if (! \BricksExtras\Helpers::elementCSSAdded($this->name) ) {
      wp_enqueue_style( 'x-event-calendar', BRICKSEXTRAS_URL . 'components/assets/css/eventcalendar.css', [], '' );
    }
  }
  
  public function render() {

    $settings = $this->settings;
    
    $eventData = [];

    $config = [
      'startView' => isset( $settings['startView'] ) ? $settings['startView'] : '',
      'viewOptions' => isset( $settings['viewOptions'] ) ? $settings['viewOptions'] : '',
      'clickAction' => isset( $settings['clickAction'] ) ? $settings['clickAction'] : 'url',
      'timeZone' => isset( $settings['timeZone'] ) ? $settings['timeZone'] : 'local',
    ];

    $this->set_attribute( '_root', 'data-x-calendar', wp_json_encode( $config ) );

    $this->set_attribute( 'x-calendar', 'class', 'x-calendar' );

    $loopIndex = false;

		if ( method_exists('\Bricks\Query','is_any_looping') ) {

			$query_id = \Bricks\Query::is_any_looping();
	
			if ( $query_id ) {
				
				if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) {
					$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(2) ) )->loop_index . '_' . \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
				} else {
					if ( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) {
						$loopIndex = \Bricks\Query::get_query_for_element_id( \Bricks\Query::get_query_element_id( BricksExtras\Helpers::get_bricks_looping_parent_query_id_by_level(1) ) )->loop_index . '_' . \Bricks\Query::get_loop_index();
					} else {
						$loopIndex = \Bricks\Query::get_loop_index();
					}
				}			
	
				$this->set_attribute( '_root', 'data-x-id', $this->id . '_' . $loopIndex );
				
			} else {
				$this->set_attribute( '_root', 'data-x-id', $this->id );
			}
	
		} 

    echo "<div {$this->render_attributes( '_root' )}>";

    echo "<div {$this->render_attributes( 'x-calendar' )}></div>";

    // Query Loop
		if ( isset( $settings['hasLoop'] ) ) {

			$query = new \Bricks\Query( [
				'id'       => $this->id,
				'settings' => $settings,
			] );

			if ( $query->count === 0 ) {
				
				$no_results_content = $query->get_no_results_content();

				if ( empty( $no_results_content ) ) {
					echo $this->render_element_placeholder( ['title' => esc_html__( 'No results', 'bricks' )] );
				}

			} else {
        
        echo $query->render( [ $this, 'render_repeater' ], $settings );
       
      }

        $query->destroy();
        unset( $query );

      }


    echo "</div>";

    wp_localize_script(
			'x-event-calendar',
			'xCalendar',
			[
				'Instances' => [],
        'Events' => [$eventData],
			]
		);

    wp_enqueue_script( 'x-popper', BRICKSEXTRAS_URL . 'components/assets/js/popper.js', '', '1.0.0', true );
	  wp_enqueue_script( 'x-popover', BRICKSEXTRAS_URL . 'components/assets/js/popover.js', ['x-popper'], '1.0.2', true );

    wp_localize_script(
			'x-popover',
			'xTippy',
			[
				'Instances' => [],
			]
		);

    if (! \BricksExtras\Helpers::elementCSSAdded('xpopover') ) {
			wp_enqueue_style( 'x-popover', BRICKSEXTRAS_URL . 'components/assets/css/popover.css', [], '' );
		}
    
  }



  public function render_repeater($settings) {

    $settings = $this->settings;
    $index    = $this->loop_index;

    $eventDatas = [];

    // Render
    ob_start();

    $eventTitle = ! empty( $settings['eventTitle'] ) ? $settings['eventTitle'] : false;
    $eventDescription = ! empty( $settings['eventDescription'] ) ? str_replace("'", "\'", $settings['eventDescription']) : false;
    $eventStart= ! empty( $settings['eventStart'] ) ? $settings['eventStart'] : false;
    $eventEnd = ! empty( $settings['eventEnd'] ) ? $settings['eventEnd'] : false;
    $eventURL = ! empty( $settings['eventURL'] ) ? $settings['eventURL'] : false;
    $eventID = ! empty( $settings['eventID'] ) ? $settings['eventID'] : false;
    $startRecur = ! empty( $settings['startRecur'] ) ? $settings['startRecur'] : false;
    $endRecur = ! empty( $settings['endRecur'] ) ? $settings['endRecur'] : false;

    $eventData = [
      'classNames' => $eventID ? $eventID : '',
      'title' => $eventTitle ? $eventTitle : '',
      'description' => $eventDescription ? $eventDescription : '',
      'start' => $eventStart ? $eventStart : '',
      'end' => $eventEnd ? $eventEnd : '',
      'url' => $eventURL ? $eventURL : '', /* https://fullcalendar.io/docs/eventClick */
      //'display' => 'background',
      //'allDay' => true
     // 'startRecur' => $startRecur ? $startRecur : '',
      //'endRecur' => $endRecur ? $endRecur : '',
      //'daysOfWeek' => [0,2]
    ];

    echo "<div data-x-event-data='". wp_json_encode($eventData) . "'></div>";

  
    $html = ob_get_clean();

    $this->loop_index++;

    return $html;
  
  }

}