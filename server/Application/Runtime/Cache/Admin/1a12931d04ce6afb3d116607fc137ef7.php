<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>OneDay</title>

    <!-- Bootstrap core CSS -->
    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/d/server/Public/wbx/dashboard.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    

<!-- DataTables Lib Start -->
<link rel="stylesheet" type="text/css" href="/d/server/Public/DataTables/datatables.min.css"/>
<script type="text/javascript" src="/d/server/Public/DataTables/datatables.min.js"></script>
<!-- DataTables Lib End -->
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#table_id').DataTable({
        "bFilter":false,
        // "ajax":'<?php echo U("Main/ajaxBuyInstructList");?>?sid=<?php echo ($sid); ?>',
        "language": {
            "sProcessing":   "处理中...",
            "sLengthMenu":   "显示 _MENU_ 项结果",
            "sZeroRecords":  "没有匹配结果",
            "sInfo":         "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
            "sInfoEmpty":    "显示第 0 至 0 项结果，共 0 项",
            "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
            "sInfoPostFix":  "",
            "sSearch":       "搜索:",
            "sUrl":          "",
            "sEmptyTable":     "表中数据为空",
            "sLoadingRecords": "载入中...",
            "sInfoThousands":  ",",
            "oPaginate": {
                "sFirst":    "首页",
                "sPrevious": "上页",
                "sNext":     "下页",
                "sLast":     "末页"
            },
            "oAria": {
                "sSortAscending":  ": 以升序排列此列",
                "sSortDescending": ": 以降序排列此列"
            }
        }
        });
    } );
</script>



</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="<?php echo U("Index/index");?>">OneDay活动发布系统</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><?php echo $userName;?></a></li>
            <li><a href="<?php echo U('User/logout');?>">退出</a></li>
          </ul>
        </div>
      </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
  <ul class="nav nav-sidebar">
    <li <?php if($nav == '1'): ?>class="active"<?php endif; ?>><a href="<?php echo U('User/index');?>">概览 <span class="sr-only">(current)</span></a></li>
    <li>基本信息维护</li>
    <li <?php if($nav == '2'): ?>class="active"<?php endif; ?>><a href="<?php echo U('User/personal');?>">基本信息维护</a></li>
  </ul>
  <ul class="nav nav-sidebar">
    <li>活动管理</li>
    <li <?php if($nav == '3'): ?>class="active"<?php endif; ?>><a href="<?php echo U('User/createActivity');?>">发布活动</a></li>
    <li <?php if($nav == '4'): ?>class="active"<?php endif; ?>><a href="<?php echo U('User/manageActivity');?>">已发布的活动</a></li>
  </ul>
</div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">已发布的活动</h1>
            <div class="row">
                <table id="table_id" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="text-align: center;vertical-align: middle">活动名称</th>
                            <th style="text-align: center;vertical-align: middle">开始时间</th>
                            <th style="text-align: center;vertical-align: middle">地点</th>
                            <th style="text-align: center;vertical-align: middle">活动状态</th>
                            <th style="text-align: center;vertical-align: middle">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td style="text-align: center;vertical-align: middle"><?php echo ($vo["title"]); ?></td>
                                <td style="text-align: center;vertical-align: middle"><?php echo ($vo["startTime"]); ?></td>
                                <td style="text-align: center;vertical-align: middle"><?php echo ($vo["location"]); ?></td>
                                <td style="text-align: center;vertical-align: middle">
                                    <?php if($vo["state"] == 0): ?>上架中<?php endif; ?>
                                    <?php if($vo["state"] == 1): ?>已下架<?php endif; ?>
                                </td>
                                <td style="text-align: center;vertical-align: middle">
                                    <a class="btn btn-primary" href='<?php echo U("Home/Activity/createActivityUserExcel");?>&aid=<?php echo ($vo["aid"]); ?>'>导出参与者名单</a>
                                    <a class="btn btn-primary" href='<?php echo U("User/viewActivity");?>&aid=<?php echo ($vo["aid"]); ?>'>查看</a>
                                    <a class="btn btn-primary" href='<?php echo U("User/editActivityState");?>&aid=<?php echo ($vo["aid"]); ?>&mode=0'>上架</a>
                                    <a class="btn btn-primary" href='<?php echo U("User/editActivityState");?>&aid=<?php echo ($vo["aid"]); ?>&mode=1'>下架</a>
                                    <a class="btn btn-primary" href='<?php echo U("User/editActivityState");?>&aid=<?php echo ($vo["aid"]); ?>&mode=2'>删除</a>
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>