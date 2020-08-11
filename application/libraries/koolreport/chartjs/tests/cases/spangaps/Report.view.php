<html>
    <head>
        <title>Test title of chartjs</title>
    </head>
    <body>
        <h1>Test spanGaps of chartjs</h1>
        <?php
        \koolreport\chartjs\LineChart::create([
            "title"=>"SpanGaps",
            "dataSource"=>[
                array("month"=>"January","sale"=>32000,"cost"=>40000),
                array("month"=>"February","sale"=>48000,"cost"=>39000),
                array("month"=>"March","sale"=>35000,"cost"=>38000),
                array("month"=>"April","sale"=>null,"cost"=>37000),
                array("month"=>"May","sale"=>60000,"cost"=>45000),
                array("month"=>"June","sale"=>73000,"cost"=>47000),
                array("month"=>"July","sale"=>null,"cost"=>60000),
                array("month"=>"August","sale"=>78000,"cost"=>65000),
                array("month"=>"September","sale"=>60000,"cost"=>45000),
                array("month"=>"October","sale"=>83000,"cost"=>71000),
                array("month"=>"November","sale"=>45000,"cost"=>40000),
                array("month"=>"December","sale"=>39000,"cost"=>60000),
            ],
            "columns"=>[
                "month",
                "sale"=>[
                    "label"=>"Sale Amount",
                    "config"=>[
                        "spanGaps"=>false
                    ]
                ]
            ],
            "options"=>[
                "scales"=>[
                    "yAxes"=>[
                        [
                            "scaleLabel"=>[
                                "display"=>true,
                                "labelString"=>"Sale Top"
                            ]
                        ]
                    ],
                    "xAxes"=>[
                        [
                            "scaleLabel"=>[
                                "display"=>true,
                                "labelString"=>"Month"
                            ]
                        ]
                    ],
                ]
            ]
        ]);
        ?>
    </body>
</html>