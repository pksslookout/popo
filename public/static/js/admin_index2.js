(function(){

    /* 设备终端 */
    // 基于准备好的dom，初始化echarts实例
    var echarts_source = echarts.init(document.getElementById('echarts_source'));
    // 指定图表的配置项和数据
    var echarts_source_option = {
        tooltip : {
            trigger: 'item',
            formatter: "{c}"
        },
        legend: {
            left: 'center',
            top:'bottom',
            itemWidth:10,
            itemHeight:10,
            formatter: '{name}',
            itemGap:50,
            textStyle:{
                color: '#000000',
                fontSize:16
            },
            data:data_source.name
        }
        ,
        calculable : true,
        series : [
            {
                type:'pie',
                radius :  ['50%', '70%'],//饼图的半径大小
                center: ['50%', '50%'],//饼图的位置
                label:{            //饼图图形上的文本标签
                    show:true,
                    textStyle : {
                        fontWeight : 300 ,
                        fontSize : 16    //文字的字体大小
                    },
                    formatter:'{d}%'
                },
                data:data_source.v_n
            }
        ]
    };
    // 使用刚指定的配置项和数据显示图表。
    echarts_source.setOption(echarts_source_option);

    /* 注册渠道 */
    // 基于准备好的dom，初始化echarts实例
    var echarts_reg = echarts.init(document.getElementById('echarts_reg'));
    // 指定图表的配置项和数据
    var echarts_reg_option = {
        tooltip : {
            trigger: 'auto',
            axisPointer : {
                type : 'shadow'
            }
        },
        xAxis : [
            {
                type : 'category',
                data : data_type.name,
                nameTextStyle:{
                    color: '#323232',
                    padding:[3,0,0,0],
                    fontSize:30
                },
                axisTick: {
                    alignWithLabel: true
                }
            }
        ],
        yAxis : [
            {
                max:'100',
                type : 'value',
                axisLabel: {
                    show: true,
                    interval: 'auto',
                    color:'#969696',
                    formatter: '{value}%'
                }
            }
        ],
        series : [
            {
                type:'bar',
                barWidth: '60%',
                data:data_type.nums_per,
                color: function (params){
                    var colorList = data_type.color;
                    return colorList[params.dataIndex];
                },
                label: {
                    show: true, //开启显示
                    position: 'top', //在上方显示
                    formatter: '{c}%',
                    textStyle: { //数值样式
                        color: '#323232',
                        fontSize: 16
                    }
                }
            }
        ]
    };
    // 使用刚指定的配置项和数据显示图表。
    echarts_reg.setOption(echarts_reg_option);

    /* 七天数据 */
    // 基于准备好的dom，初始化echarts实例
    var echarts_week = echarts.init(document.getElementById('echarts_week'));
    // 指定图表的配置项和数据
    var echarts_week_option = {
        legend: {
            left: 'right',
            itemWidth:10,
            itemHeight:10,
            formatter: '{name}',
            itemGap:50,
            textStyle:{
                color: '#000000',
                fontSize:16
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: data_week.date
        },
        yAxis: {
            type: 'value',
            minInterval:'1'
        },
        tooltip : {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        series: [{
            name:'新增视频',
            data: data_week.value,
            type: 'line',
            smooth: true,
            lineStyle:{
                color:'#4da2ff'
            },
            itemStyle: {
                color: '#4da2ff',
            },
            symbolSize:10, //折线点的大小
            areaStyle: {
                color: {
                    type: 'linear',
                    x: 0,
                    y: 0,
                    x2: 0,
                    y2: 1,
                    colorStops: [{
                        offset: 0, color: '#4da2ff' // 0% 处的颜色
                    }, {
                        offset: 1, color: '#fff' // 100% 处的颜色
                    }],
                    global: false // 缺省为 false
                }
            }
        },{
            name:'点赞数',
            data: data_week.likes,
            type: 'line',
            smooth: true,
            lineStyle:{
                color:'#ff7a8b'
            },
            itemStyle: {
                color: '#ff7a8b',
            },
            symbolSize:10, //折线点的大小
            areaStyle: {
                color: {
                    type: 'linear',
                    x: 0,
                    y: 0,
                    x2: 0,
                    y2: 1,
                    colorStops: [{
                        offset: 0, color: '#ff7a8b' // 0% 处的颜色
                    }, {
                        offset: 1, color: '#fff' // 100% 处的颜色
                    }],
                    global: false // 缺省为 false
                }
            }
        },{
            name:'粉丝数',
            data: data_week.fans,
            type: 'line',
            smooth: true,
            lineStyle:{
                color:'#83d688'
            },
            itemStyle: {
                color: '#83d688',
            },
            symbolSize:10, //折线点的大小
            areaStyle: {
                color: {
                    type: 'linear',
                    x: 0,
                    y: 0,
                    x2: 0,
                    y2: 1,
                    colorStops: [{
                        offset: 0, color: '#83d688' // 0% 处的颜色
                    }, {
                        offset: 1, color: '#fff' // 100% 处的颜色
                    }],
                    global: false // 缺省为 false
                }
            }
        }]
    };
    // 使用刚指定的配置项和数据显示图表。
    echarts_week.setOption(echarts_week_option);

    /* 广告数据 */
    // 基于准备好的dom，初始化echarts实例
    var echarts_ad = echarts.init(document.getElementById('echarts_ad'));
    // 指定图表的配置项和数据
    var echarts_ad_option = {
        legend: {
            left: 'right',
            itemWidth:10,
            itemHeight:10,
            formatter: '{name}',
            itemGap:50,
            textStyle:{
                color: '#000000',
                fontSize:16
            },
            data:['广告数量','浏览数量']
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: data_ad.date
        },
        yAxis: {
            type: 'value',
            minInterval:'1'
        },
        tooltip : {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        series: [{
            name:'广告数量',
            data: data_ad.value,
            type: 'line',
            smooth: true,
            symbol:'circle',
            lineStyle:{
                color:'#9e80fc'
            },
            itemStyle: {
                color: '#9e80fc',
            }
        },
            {
                name:'浏览数量',
                data: data_ad.videoviews,
                type: 'line',
                smooth: true,
                symbol:'circle',
                lineStyle:{
                    color:'#ff7a8b'
                },
                itemStyle: {
                    color: '#ff7a8b',
                }
            }]
    };
    // 使用刚指定的配置项和数据显示图表。
    echarts_ad.setOption(echarts_ad_option);



    /* ajax */
    function getData(request_data){
        $.ajax({
            url:'/admin/main/getdata.html',
            type:'POST',
            data:request_data,
            dataType:'json',
            success:function(data){
                var code=data.code;
                var info=data.msg;
                if(code!=1){
                    alert(info);
                    //parent.location.reload();
                    return !1;
                }

                var action=request_data.action;
                switch(action){
                    case '1':
                        /* 基本指标 */
                        $(".basic_list li.newusers .basic_list_n span").text(info.newusers);
                        $(".basic_list li.launches .basic_list_n span").text(info.launches);
                        $(".basic_list li.durations .basic_list_n span").text(info.durations);
                        $(".basic_list li.activityusers .basic_list_n span").text(info.activeusers);
                        //$(".basic_list li.users_total .basic_list_n span").text(info.nums);
                        break;
                    case '2':
                        /* 七天数据 */
                        echarts_week_option.xAxis.data=[];
                        echarts_week_option.series[0].data=[];
                        echarts_week.setOption(echarts_week_option);
                        break;
                    case '3':
                        /* 广告数据 */
                        echarts_ad_option.xAxis.data=info.date;
                        echarts_ad_option.series[0].data=info.value;
                        echarts_ad_option.series[1].data=info.videoviews;
                        echarts_ad.setOption(echarts_ad_option);
                        break;
                }
            },
            error:function(){

            }
        })
    }


    $(".search").click(function(){
        var _this=$(this);
        var start_time=_this.parents('.bd_title').find("input[name=start_time]").val();
        var end_time=_this.parents('.bd_title').find("input[name=end_time]").val();

        var action=_this.parents('.bd_title').find(".action").val();


        var request_data={action:action,start_time:start_time,end_time:end_time};
        getData(request_data);
    })


    $(".export").click(function(){
        var _this=$(this);

        var action=_this.parents('.bd_title').find(".action").val();
        var start_time=_this.parents('.bd_title').find("input[name=start_time]").val();
        var end_time=_this.parents('.bd_title').find("input[name=end_time]").val();


        location.href='/admin/main/export.html&action='+action+'&start_time='+start_time+'&end_time='+end_time;
    })

})()