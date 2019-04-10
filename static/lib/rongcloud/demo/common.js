(function () {
/*
    将相同代码拆出来方便维护
 */
window.RongDemo = {
    common: function (WebIMWidget, config, $scope, $http) {
        WebIMWidget.init(config);

        WebIMWidget.setUserInfoProvider(function(targetId,obj){
            $http({
                url:"getChatUserInfo/userid/"+targetId
            }).success(function(user){
                if(user){
                    if(targetId.indexOf('shop')!=-1){
                        obj.onSuccess({id:'shop'+user.id,name:user.shopName,portraitUri:user.headImg});
                    }else{
                        obj.onSuccess({id:'user'+user.id,name:user.nickname,portraitUri:user.headsmall});
                    }
                }else{
                    obj.onSuccess({id:targetId,name:"用户："+targetId});
                }
            })
        });

        WebIMWidget.setGroupInfoProvider(function(targetId, obj){
            obj.onSuccess({
                name:'群组：' + targetId
            });
        })

        $scope.setconversation = function () {
            if (!!$scope.targetId) {
                WebIMWidget.setConversation(Number($scope.targetType), $scope.targetId, "用户：" + $scope.targetId);
                WebIMWidget.show();
            }
        };

        // $scope.customerserviceId = "KEFU145914839332836";
        // $scope.setcustomerservice = function () {
        //     WebIMWidget.setConversation(Number(RongIMLib.ConversationType.CUSTOMER_SERVICE), $scope.customerserviceId);
        //     WebIMWidget.show();
        // }

        $scope.show = function() {
            WebIMWidget.show();
        };

        $scope.hidden = function() {
            WebIMWidget.hidden();
        };

        WebIMWidget.show();
        

        // 示例：获取 userinfo.json 中数据，根据 targetId 获取对应用户信息
        // WebIMWidget.setUserInfoProvider(function(targetId,obj){
        //     $http({
        //       url:"getChatUserInfo/userid/"+targetId
        //     }).success(function(user){
        //       if(user){
        //         obj.onSuccess({id:'shop'+user.id,name:user.shopName,portraitUri:user.headImg});
        //       }else{
        //         obj.onSuccess({id:targetId,name:"用户："+targetId});
        //       }
        //     })
        // });

        // 示例：获取 online.json 中数据，根据传入用户 id 数组获取对应在线状态
        // WebIMWidget.setOnlineStatusProvider(function(arr, obj) {
        //     $http({
        //         url: "/online.json"
        //     }).success(function(rep) {
        //         obj.onSuccess(rep.data);
        //     })
        // });
    }
}

})()