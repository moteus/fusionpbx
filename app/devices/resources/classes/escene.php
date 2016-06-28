<?php

class escene {
	const vendor = 'escene';
	const title  = 'Escene';

	const memory_key_functions = Array(
		Array(1,  'label-blf'              ),
		// Array(3,  'label-bla'              ),
		Array(7,  'label-call_park'        ),
		Array(4,  'label-dtmf'             ),
		Array(5,  'label-speed_dial'       ),
		Array(2,  'label-speed_dial_prefix'),
		Array(8,  'label-intercom'         ),
		Array(9,  'label-pickup'           ),
		Array(6,  'label-paging'           ),
		Array(11, 'label-broadsoft_group'  ),
	);

	const program_key_functions = Array(
		Array(0,  'label-default'         ),  /* 0  - Default                    */
		Array(1,  'label-redial'          ),  /* 1  - Redial                     */
		Array(2,  'label-dnd'             ),  /* 2  - DND                        */
		Array(3,  'label-phone_book'      ),  /* 3  - Contacts                   */
		Array(4,  'label-ent_phone_book'  ),  /* 4  - Enterprise Phonebook       */
		Array(5,  'label-ldap'            ),  /* 5  - LDAP                       */
		Array(6,  'label-directory'       ),  /* 6  - Dir                        */
		Array(7,  'label-speed_dial'      ),  /* 7  - Speed Dial                 */
		Array(8,  'label-call_log'        ),  /* 8  - Call List                  */
		Array(9,  'label-missed_calls'    ),  /* 9  - Missed Calls               */
		Array(10, 'label-received_calls'  ),  /* 10 - Received Calls             */
		Array(11, 'label-dialed_calls'    ),  /* 11 - Dialed Calls               */
		Array(12, 'label-menu'            ),  /* 12 - Menu                       */
		Array(13, 'label-sms'             ),  /* 13 - SMS                        */
		Array(14, 'label-new_sms'         ),  /* 14 - New SMS                    */
		Array(15, 'label-forward'         ),  /* 15 - Call Forward               */
		Array(16, 'label-status'          ),  /* 16 - View Status                */
		Array(17, 'label-enable_account'  ),  /* 17 - Enable/Disable SIP Account */
		Array(19, 'label-provison_now'    ),  /* 19 - Auto Provison Now          */
		Array(20, 'label-hot_desking'     ),  /* 20 - Hot Desking                */
		// Array(18, 'label-xml browser'     ),  /* 18 - XML Browser                */
	);

	const time_zones = Array(
		Array(12, "GMT Greenwich Mean Time"                                   ),
		Array(67, "GMT+00:00 Denmark-Faroe Islands(Torshavn)"                 ),
		Array(66, "GMT+00:00 Greenland"                                       ),
		Array(68, "GMT+00:00 Irelan(Dublin)"                                  ),
		Array(72, "GMT+00:00 Morocco"                                         ),
		Array(69, "GMT+00:00 Portugal(Lisboa, Porto, Funchal)"                ),
		Array(70, "GMT+00:00 Spain-Canary Islands(Las Palmas)"                ),
		Array(71, "GMT+00:00 United-Kingdom(London)"                          ),
		Array(73, "GMT+01:00 Albania(Tirane)"                                 ),
		Array(74, "GMT+01:00 Austria(Vienna)"                                 ),
		Array(75, "GMT+01:00 Belgium(Brussels)"                               ),
		Array(76, "GMT+01:00 Caicos"                                          ),
		Array(77, "GMT+01:00 Chad"                                            ),
		Array(79, "GMT+01:00 Croatia(Zagreb)"                                 ),
		Array(80, "GMT+01:00 Czech Republic(Prague)"                          ),
		Array(81, "GMT+01:00 Denmark(Kopenhagen)"                             ),
		Array(82, "GMT+01:00 France(Paris)"                                   ),
		Array(13, "GMT+01:00 Germany(Berlin)"                                 ),
		Array(83, "GMT+01:00 Hungary(Budapest)"                               ),
		Array(84, "GMT+01:00 Italy(Rome)"                                     ),
		Array(85, "GMT+01:00 Luxembourg(Luxembourg)"                          ),
		Array(86, "GMT+01:00 Macedonia(Skopje)"                               ),
		Array(88, "GMT+01:00 Namibia(Windhoek)"                               ),
		Array(87, "GMT+01:00 Netherlands(Amsterdam)"                          ),
		Array(78, "GMT+01:00 Spain(Madrid)"                                   ),
		Array(14, "GMT+02:00 Egypt(Cairo)"                                    ),
		Array(89, "GMT+02:00 Estonia(Tallinn)"                                ),
		Array(90, "GMT+02:00 Finland(Helsinki)"                               ),
		Array(91, "GMT+02:00 Gaza Strip(Gaza)"                                ),
		Array(92, "GMT+02:00 Greece(Athens)"                                  ),
		Array(45, "GMT+02:00 Israel(Tel Aviv)"                                ),
		Array(93, "GMT+02:00 Jordan(Amman)"                                   ),
		Array(94, "GMT+02:00 Latvia(Riga)"                                    ),
		Array(95, "GMT+02:00 Lebanon(Beirut)"                                 ),
		Array(96, "GMT+02:00 Moldova(Kishinev)"                               ),
		Array(48, "GMT+02:00 Romania(Bucharest)"                              ),
		Array(97, "GMT+02:00 Russia(Kaliningrad)"                             ),
		Array(98, "GMT+02:00 Syria(Damascus)"                                 ),
		Array(99, "GMT+02:00 Turkey(Ankara)"                                  ),
		Array(100,"GMT+02:00 Ukraine(Kyiv, Odessa)"                           ),
		Array(15, "GMT+03:00 Bahrain,Russia(Moscow)"                          ),
		Array(101,"GMT+03:00 East Africa Time"                                ),
		Array(102,"GMT+03:00 Iraq(Baghdad)"                                   ),
		Array(47, "GMT+03:30 Iran(Teheran)"                                   ),
		Array(103,"GMT+04:00 Armenia(Yerevan)"                                ),
		Array(104,"GMT+04:00 Azerbaijan(Baku)"                                ),
		Array(105,"GMT+04:00 Georgia(Tbilisi)"                                ),
		Array(106,"GMT+04:00 Kazakhstan(Aktau)"                               ),
		Array(16, "GMT+04:00 Muscat"                                          ),
		Array(107,"GMT+04:00 Russia(Samara)"                                  ),
		Array(108,"GMT+04:30 Afghanistan"                                     ),
		Array(109,"GMT+05:00 Kazakhstan(Aqtobe)"                              ),
		Array(110,"GMT+05:00 Kyrgyzstan(Bishkek)"                             ),
		Array(17, "GMT+05:00 Pakistan(Islamabad)"                             ),
		Array(111,"GMT+05:00 Russia(Chelyabinsk)"                             ),
		Array(26, "GMT+05:30 India"                                           ),
		Array(112,"GMT+06:00 Kazakhstan(Astana, Almaty)"                      ),
		Array(18, "GMT+06:00 Russia(Novosibirsk, Omsk)"                       ),
		Array(46, "GMT+06:30 Myanmar"                                         ),
		Array(113,"GMT+07:00 Russia(Krasnoyarsk)"                             ),
		Array(19, "GMT+07:00 Thailand(Bangkok)"                               ),
		Array(115,"GMT+08:00 Australia(Perth)"                                ),
		Array(20, "GMT+08:00 China(Beijing)"                                  ),
		Array(114,"GMT+08:00 Singapore(Singapore)"                            ),
		Array(21, "GMT+09:00 Japan(Tokyo)"                                    ),
		Array(116,"GMT+09:00 Korea(Seoul)"                                    ),
		Array(117,"GMT+09:30 Australia(Adelaide, Darwin)"                     ),
		Array(44, "GMT+09:30 Australia-Central"                               ),
		Array(118,"GMT+10:00 Russia(Vladivostok)"                             ),
		Array(22, "GMT+10:00 Sydney,Melbourne,Canberra,Brisbane,Hobart"       ),
		Array(119,"GMT+10:30 Australia(Lord Howe Islands)"                    ),
		Array(120,"GMT+11:00 New Caledonia(Noumea)"                           ),
		Array(23, "GMT+11:00 Solomon Islands"                                 ),
		Array(24, "GMT+12:00 New Zealand(Wellington, Auckland)"               ),
		Array(121,"GMT+12:45 New Zealand(Chatham Islands)"                    ),
		Array(25, "GMT+13:00 Tonga(Nukualofa)"                                ),
		Array(11, "GMT-01:00 Arquipelago Dos Acores"                          ),
		Array(65, "GMT-01:00 Portugal(Azores)"                                ),
		Array(10, "GMT-02:00 UTC-02"                                          ),
		Array(64, "GMT-03:00 Argentina(Buenos Aires)"                         ),
		Array(9,  "GMT-03:00 Brasil(Brasilia)"                                ),
		Array(63, "GMT-03:00 Denmark-Greenland(Nuuk)"                         ),
		Array(43, "GMT-03:30 Canada-Newfoundland(St. Johns)"                  ),
		Array(42, "GMT-04:00 Canada-Atlantic"                                 ),
		Array(59, "GMT-04:00 Paraguay(Asuncion)"                              ),
		Array(8,  "GMT-04:00 Santiago de Chile"                               ),
		Array(62, "GMT-04:00 Trinidad &amp; Tobago"                           ),
		Array(61, "GMT-04:00 United kingdom(Falkland Islands)"                ),
		Array(60, "GMT-04:00 United kingdom-Bermuda(Bermuda)"                 ),
		Array(58, "GMT-04:30 Venezuela(Caracas)"                              ),
		Array(55, "GMT-05:00 Bahamas(Nassau)"                                 ),
		Array(56, "GMT-05:00 Canada(Montreal, Ottawa, Quebec)"                ),
		Array(57, "GMT-05:00 Cuba(Havana)"                                    ),
		Array(7,  "GMT-05:00 Lima"                                            ),
		Array(38, "GMT-05:00 US-East-Indiana"                                 ),
		Array(32, "GMT-05:00 US-Eastern"                                      ),
		Array(34, "GMT-05:00 US-Michigan"                                     ),
		Array(53, "GMT-06:00 Canada-Manitoba(Winnipeg)"                       ),
		Array(54, "GMT-06:00 Chile(Easter Islands)"                           ),
		Array(6,  "GMT-06:00 Mexico(Mexico City, Acapulco)"                   ),
		Array(37, "GMT-06:00 US-Central"                                      ),
		Array(39, "GMT-06:00 US-Indiana-Starke"                               ),
		Array(51, "GMT-07:00 Canada(Edmonton, Calgary)"                       ),
		Array(5,  "GMT-07:00 LaPaz"                                           ),
		Array(52, "GMT-07:00 Mexico(Mazatlan, Chihuahua)"                     ),
		Array(31, "GMT-07:00 US-Arizona"                                      ),
		Array(40, "GMT-07:00 US-Mountain"                                     ),
		Array(4,  "GMT-08:00 California"                                      ),
		Array(49, "GMT-08:00 Canada(Vancouver, Whitehorse)"                   ),
		Array(50, "GMT-08:00 Mexico(Tijuana, Mexicali)"                       ),
		Array(35, "GMT-08:00 US-Pacific"                                      ),
		Array(30, "GMT-09:00 US-Alaska"                                       ),
		Array(3,  "GMT-09:00 UTC-9"                                           ),
		Array(36, "GMT-10:00 US-Aleutian"                                     ),
		Array(33, "GMT-10:00 US-Hawaii"                                       ),
		Array(2,  "GMT-10:00 UTC-10"                                          ),
		Array(41, "GMT-11:00 US-Samoa"                                        ),
		Array(1,  "GMT-11:00 UTC-11"                                          ),
		Array(0,  "GMT-12:00 UTC-12"                                          ),
	);

	const date_formats = Array(
		Array(0, "DD MM WWW"  ),
		Array(1, "MM DD WWW"  ),
		Array(2, "WWW MMM DD" ),
		Array(3, "DD-MMM-YY"  ),
		Array(4, "YYYY-MM-DD" ),
		Array(5, "DD/MM/YYYY" ),
		Array(6, "MM/DD/YY"   ),
		Array(7, "DD MMM YYYY"),
		Array(8, "WWW DD MMM" ),
	);

	const time_formats = Array(
		Array(0, "24 Hour"  ),
		Array(1, "12 Hour"  ),
	);

	public static function time_zone_name($id){
		foreach(self::time_zones as &$time_zone){
			if($time_zone[0] == $id){
				return $time_zone[1];
			}
		}
		return $id;
	}

	public static function time_zone_id($name){
		foreach(self::time_zones as &$time_zone){
			if($time_zone[1] == $name){
				return $time_zone[0];
			}
		}
		return $name;
	}

	public static function date_format_name($id){
		foreach(self::date_formats as &$date_format){
			if($date_format[0] == $id){
				return $date_format[1];
			}
		}
		return $id;
	}

	public static function date_format_id($name){
		foreach(self::date_formats as &$date_format){
			if($date_format[1] == $name){
				return $date_format[0];
			}
		}
		return $name;
	}

	public static function time_format_name($id){
		foreach(self::time_formats as &$time_format){
			if($time_format[0] == $id){
				return $time_format[1];
			}
		}
		return $id;
	}

	public static function time_format_id($name){
		foreach(self::time_formats as &$time_format){
			if($time_format[1] == $name){
				return $time_format[0];
			}
		}
		return $name;
	}
};
