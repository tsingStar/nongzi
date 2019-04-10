<?php
/**
 * 支付完成主流程后续添加实体类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018.6.6
 * Time: 11:55
 */

namespace app\common\model;


class PayResultOther
{
    /**
     * 设置用户积分
     * @param $order
     * @return bool
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function setScore($order)
    {
        $scoreSet = model('ScoreSet')->find();
        if($scoreSet['is_ratio_score'] == 1){
            //线上消费是否赠送积分
            $ratio = $scoreSet['ratio_score'];
            $userScore = new ScoreLog();
            $score = round($order['real_cost']*$ratio, 2);
            $userScore->save(['score'=>$score, 'type'=>1, 'user_id'=>$order['user_id'], 'desc'=>'消费赠送积分']);
            $user = model('user')->where('id', $order['user_id'])->find();
            $user->setInc('score', $score);
            $sixun = new SixunOpera();
            $card = $sixun->getCardInfo($user['card_id']);
            $score += $card['acc_num'];
            $sixun->set_core($score, $user['card_id']);
        }
        self::otherWelfare($order);
        return true;
    }

    /**
     * 其他福利赠送
     */
    public static function otherWelfare($order)
    {
        return true;
    }

}