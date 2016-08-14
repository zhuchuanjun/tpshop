<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>tpshop管理后台</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="/Public/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
 	<link href="/Public/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 --
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="/Public/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
    	folder instead of downloading all of them to reduce the load. -->
    <link href="/Public/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="/Public/plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />   
    <!-- jQuery 2.1.4 -->
    <script src="/Public/plugins/jQuery/jQuery-2.1.4.min.js"></script>
	<script src="/Public/js/global.js"></script>
    <script src="/Public/js/myFormValidate.js"></script>    
    <script src="/Public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/Public/js/layer/layer-min.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
    <script src="/Public/js/myAjax.js"></script>
    <script type="text/javascript">
    function delfunc(obj){
    	layer.confirm('确认删除？', {
    		  btn: ['确定','取消'] //按钮
    		}, function(){
    		    // 确定
   				$.ajax({
   					type : 'post',
   					url : $(obj).attr('data-url'),
   					data : {act:'del',del_id:$(obj).attr('data-id')},
   					dataType : 'json',
   					success : function(data){
   						if(data==1){
   							layer.msg('操作成功', {icon: 1});
   							$(obj).parent().parent().remove();
   						}else{
   							layer.msg(data, {icon: 2,time: 2000});
   						}
   						layer.closeAll();
   					}
   				})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }
    
    function selectAll(name,obj){
    	$('input[name*='+name+']').prop('checked', $(obj).checked);
    }   
    
    function get_help(obj){
        layer.open({
            type: 2,
            title: '帮助手册',
            shadeClose: true,
            shade: 0.3,
            area: ['90%', '90%'],
            content: $(obj).attr('data-url'), 
        });
    }
    </script>        
  </head>
  <body style="background-color:#ecf0f5;">
 

<link href="/Public/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
<script src="/Public/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="/Public/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<div class="wrapper">
    <div class="breadcrumbs" id="breadcrumbs">
	<ol class="breadcrumb">
	<?php if(is_array($navigate_admin)): foreach($navigate_admin as $k=>$v): if($k == '后台首页'): ?><li><a href="<?php echo ($v); ?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo ($k); ?></a></li>
	    <?php else: ?>    
	        <li><a href="<?php echo ($v); ?>"><?php echo ($k); ?></a></li><?php endif; endforeach; endif; ?>          
	</ol>
</div>

    <section class="content">
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i>分成记录</h3>
                </div>
                <div class="panel-body">
                    <div class="navbar navbar-default">
                        <form id="search-form2" class="navbar-form form-inline"  method="post" action="/index.php/Admin/Distribut/rebate_log">
                            <div class="form-group">
                                <label for="input-order-id" class="control-label">状态:</label>
                                <div class="form-group">
                                    <select class="form-control" id="status" name="status">
                                        <option value="">全部</option>
                                        <option value="0">未付款</option>
                                        <option value="1">已付款</option>
                                        <option value="2">等待分成</option>
                                        <option value="3">已分成</option>
                                        <option value="4">已取消</option>
                                    </select>
                                </div>
                                <label for="input-order-id" class="control-label">用户ID:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="user_id" placeholder="用户id" value="" name="user_id">
                                </div>

                                <label for="input-order-id" class="control-label">订单号:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="input-order-id" placeholder="订单编号" value="" name="order_sn">
                                </div>

                                <div class="input-group margin">
                                    <div class="input-group-addon">
                                        记录生成时间<i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" id="start_time" value="2015-08-04 - 2016-08-05" name="create_time" class="form-control pull-right" style="width:250px;">
                                </div>

                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" id="button-filter search-order" type="submit"><i class="fa fa-search"></i> 筛选</button>
                            </div>
                        </form>
                    </div>

                    <div id="ajax_return">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="sorting text-left">ID</th>
                                    <th class="sorting text-left">获佣用户id</th>
                                    <th class="sorting text-left">订单编号</th>
                                    <th class="sorting text-left">获佣金额</th>
                                    <th class="sorting text-left">获佣用户级别</th>
                                    <th class="sorting text-left">记录生成时间</th>
                                    <th class="sorting text-left">状态</th>
                                    <th class="sorting text-left">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-left">117</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/573">
                                            573                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/399">
                                            201607061812366062                                        </a>
                                    </td>
                                    <td class="text-left">276.00</td>
                                    <td class="text-left">1</td>
                                    <td class="text-left">2016-07-06</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/117" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">116</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/541">
                                            541                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/396">
                                            201607042214012294                                        </a>
                                    </td>
                                    <td class="text-left">586.80</td>
                                    <td class="text-left">2</td>
                                    <td class="text-left">2016-07-04</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/116" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">115</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/542">
                                            542                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/396">
                                            201607042214012294                                        </a>
                                    </td>
                                    <td class="text-left">1408.32</td>
                                    <td class="text-left">1</td>
                                    <td class="text-left">2016-07-04</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/115" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">114</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/532">
                                            532                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/394">
                                            201607041653061035                                        </a>
                                    </td>
                                    <td class="text-left">1.50</td>
                                    <td class="text-left">3</td>
                                    <td class="text-left">2016-07-04</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/114" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">113</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/533">
                                            533                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/394">
                                            201607041653061035                                        </a>
                                    </td>
                                    <td class="text-left">2.50</td>
                                    <td class="text-left">2</td>
                                    <td class="text-left">2016-07-04</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/113" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">112</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/535">
                                            535                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/394">
                                            201607041653061035                                        </a>
                                    </td>
                                    <td class="text-left">6.00</td>
                                    <td class="text-left">1</td>
                                    <td class="text-left">2016-07-04</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/112" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">111</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/521">
                                            521                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/393">
                                            201607041232542790                                        </a>
                                    </td>
                                    <td class="text-left">2.07</td>
                                    <td class="text-left">3</td>
                                    <td class="text-left">2016-07-04</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/111" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">110</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/522">
                                            522                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/393">
                                            201607041232542790                                        </a>
                                    </td>
                                    <td class="text-left">3.45</td>
                                    <td class="text-left">2</td>
                                    <td class="text-left">2016-07-04</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/110" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">109</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/529">
                                            529                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/393">
                                            201607041232542790                                        </a>
                                    </td>
                                    <td class="text-left">8.28</td>
                                    <td class="text-left">1</td>
                                    <td class="text-left">2016-07-04</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/109" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">108</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/477">
                                            477                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/392">
                                            201607041122067797                                        </a>
                                    </td>
                                    <td class="text-left">13.20</td>
                                    <td class="text-left">1</td>
                                    <td class="text-left">2016-07-04</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/108" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">107</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/477">
                                            477                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/391">
                                            201607041118087418                                        </a>
                                    </td>
                                    <td class="text-left">13.20</td>
                                    <td class="text-left">1</td>
                                    <td class="text-left">2016-07-04</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/107" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">106</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/344">
                                            344                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/390">
                                            201607032356181707                                        </a>
                                    </td>
                                    <td class="text-left">26.40</td>
                                    <td class="text-left">1</td>
                                    <td class="text-left">2016-07-03</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/106" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">105</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/175">
                                            175                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/389">
                                            201607031628506085                                        </a>
                                    </td>
                                    <td class="text-left">419.88</td>
                                    <td class="text-left">1</td>
                                    <td class="text-left">2016-07-03</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/105" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">104</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/175">
                                            175                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/388">
                                            201607031621069672                                        </a>
                                    </td>
                                    <td class="text-left">400.00</td>
                                    <td class="text-left">2</td>
                                    <td class="text-left">2016-07-03</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/104" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">103</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/208">
                                            208                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/388">
                                            201607031621069672                                        </a>
                                    </td>
                                    <td class="text-left">960.00</td>
                                    <td class="text-left">1</td>
                                    <td class="text-left">2016-07-03</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/103" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr><tr>
                                    <td class="text-left">102</td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/user/detail/id/175">
                                            175                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/order/detail/order_id/387">
                                            201607031612432018                                        </a>
                                    </td>
                                    <td class="text-left">399.70</td>
                                    <td class="text-left">2</td>
                                    <td class="text-left">2016-07-03</td>
                                    <td class="text-left">
                                        未付款
                                    </td>
                                    <td class="text-left">
                                        <a href="/index.php/Admin/Distribut/editRebate/id/102" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <!--
                                        <a href="javascript:void(0);" onclick="del('')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        -->
                                    </td>
                                </tr>                            </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 text-left"></div>
                            <div class="col-sm-6 text-right"><div class='dataTables_paginate paging_simple_numbers'><ul class='pagination'>  <li class="paginate_button active"><a tabindex="0" data-dt-idx="1" aria-controls="example1" href="#">1</a></li><li class="paginate_button"><a class="num" href="/index.php/Admin/Distribut/rebate_log/p/2">2</a></li><li class="paginate_button"><a class="num" href="/index.php/Admin/Distribut/rebate_log/p/3">3</a></li><li class="paginate_button"><a class="num" href="/index.php/Admin/Distribut/rebate_log/p/4">4</a></li><li class="paginate_button"><a class="num" href="/index.php/Admin/Distribut/rebate_log/p/5">5</a></li><li class="paginate_button"><a class="num" href="/index.php/Admin/Distribut/rebate_log/p/6">6</a></li><li class="paginate_button"><a class="num" href="/index.php/Admin/Distribut/rebate_log/p/7">7</a></li><li class="paginate_button"><a class="num" href="/index.php/Admin/Distribut/rebate_log/p/8">8</a></li> <li id="example1_next" class="paginate_button next"><a class="next" href="/index.php/Admin/Distribut/rebate_log/p/2">下一页</a></li> </ul></div></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

    </section>
</div>
<script>
    $(document).ready(function() {
        $('#start_time').daterangepicker({
            format:"YYYY-MM-DD",
            singleDatePicker: false,
            showDropdowns: true,
            minDate:'2016-01-01',
            maxDate:'2030-01-01',
            startDate:'2016-07-01',
            locale : {
                applyLabel : '确定',
                cancelLabel : '取消',
                fromLabel : '起始时间',
                toLabel : '结束时间',
                customRangeLabel : '自定义',
                daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月','七月', '八月', '九月', '十月', '十一月', '十二月' ],
                firstDay : 1
            }
        });
    });

</script>

</body>
</html>