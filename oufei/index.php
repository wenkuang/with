<?php
require_once 'header.php';
$of = new oufei_page();
$orders = $of->get_orders();
?>
<div class="container">
    <div>
        <div class="row">
            <div class="col-md-1">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">订单<span class="caret"></span></button>
                    <span class="dropdown-arrow dropdown-arrow-inverse"></span>
                    <ul class="dropdown-menu dropdown-inverse">
                        <li><a href="#fakelink">成功订单</a></li>
                        <li><a href="#fakelink">失败订单</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-1">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">费用<span class="caret"></span></button>
                    <span class="dropdown-arrow dropdown-arrow-inverse"></span>
                    <ul class="dropdown-menu dropdown-inverse">
                        <li><a href="#fakelink">10元</a></li>
                        <li><a href="#fakelink">120元</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <input type="text" value="" placeholder="开始时间" class="span3 datetimepicker">
                <input type="text" value="" placeholder="结束时间" class="span3 datetimepicker">&nbsp;&nbsp;
                <button type="button" class="btn btn-primary">查询</button>
                
            </div>
            
            
        </div>
    </div>
    <table class="table">
        <tr>
            <th>用户ID</th>
            <th>费用</th>
            <th>支付时间</th>
            <th>支付状态</th>
        </tr>
        <?php
        if (!empty($orders)) {
            foreach ($orders as $order) {
                ?>
                <tr>
                    <td style="width:120px;"><?php echo $order['uid']; ?></td>
                    <td><?php echo $order['fee']; ?></td>
                    <td><?php echo $order['pay_time']; ?></td>
                    <td><?php
                        if ($order['status'] == 1) {
                            echo "已开通";
                        } elseif ($order['status'] == 2) {
                            echo "退订";
                        } else {
                            echo "未支付";
                        }
                        ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</div>

<?php
include_once 'footer.php';
?>