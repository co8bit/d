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
    <link href="/ondeday/server/Public/wbx/dashboard.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    
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
            <form name="person" enctype="multipart/form-data" method="post" >
            <h1 class="page-header">基本信息维护</h1>
            <?php if($edit == '1'): ?><div class="alert alert-success" role="alert">修改成功</div><?php endif; ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td>姓名*</td>
                        <td><input name="realName" type="text" class="form-control" value="<?php echo ($data["realName"]); ?>"/></td>
                    </tr>
                    <tr>
                        <td>身份证号*</td>
                        <td><input name="idcard" type="text" class="form-control" value="<?php echo ($data["idcard"]); ?>"/></td>
                    </tr>
                    <tr>
                        <td>手机号*</td>
                        <td>
                            <input name="phone" type="text" class="form-control" value="<?php echo ($data["phone"]); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>邮箱*</td>
                        <td>
                            <input name="email" type="text" class="form-control" value="<?php echo ($data["email"]); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>当前任职机构*</td>
                        <td>
                            <input name="corp" type="text" class="form-control" value="<?php echo ($data["corp"]); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>职务*</td>
                        <td>
                            <input name="career" type="text" class="form-control" value="<?php echo ($data["career"]); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>上传名片*</td>
                        <td>
                            <input name="businessCard" type="file" class="form-control"/>
                        </td>
                    </tr>
                    <tr>
                        <td>机构授权书*</td>
                        <td>
                            <input name="corpBook" type="file" class="form-control"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-6"><button type="button" class="btn btn-danger"><?php echo ($msg); ?></button></div>
                <div class="col-md-6"><input type="submit" class="btn btn-primary" value="修改"/></div>
            </div>
            </form>
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