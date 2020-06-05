function isnull(val) {
    if (val == '' || val == undefined || val == null)
    {
        return true;
    }
    var str = val.replace(/(^\s*)|(\s*$)/g, '');//去除空格;
    if (str == '' || str == undefined || str == null) {
        return true;
    } else {
        return false;
    }
}

function accept_string(str)
{
    var reg = /^[0-9A-Za-z_]+$/g;
    return reg.test(str);
}

var cookie = {
    set:function(key,val){//,time){//设置cookie方法
        var date=new Date(); //获取当前时间
        //var expiresDays=time;  //将date设置为n天以后的时间
        //date.setTime(date.getTime()+expiresDays*24*3600*1000); //格式化为cookie识别的时间
        document.cookie=key+ "=" + val +";";//expires="+date.toGMTString();  //设置cookie
    },
    get:function(key){//获取cookie方法
        /*获取cookie参数*/
        var getCookie = document.cookie.replace(/[ ]/g,"");  //获取cookie，并且将获得的cookie格式化，去掉空格字符
        var arrCookie = getCookie.split(";")  //将获得的cookie以"分号"为标识 将cookie保存到arrCookie的数组中
        var tips;  //声明变量tips
        for(var i=0;i<arrCookie.length;i++){   //使用for循环查找cookie中的tips变量
            var arr=arrCookie[i].split("=");   //将单条cookie用"等号"为标识，将单条cookie保存为arr数组
            if(key==arr[0]){  //匹配变量名称，其中arr[0]是指的cookie名称，如果该条变量为tips则执行判断语句中的赋值操作
                tips=arr[1];   //将cookie的值赋给变量tips
                break;   //终止for循环遍历
            }
        }
        return tips;
    },
    delete:function(key){ //删除cookie方法
        var date = new Date(); //获取当前时间
        date.setTime(date.getTime()-10000); //将date设置为过去的时间
        document.cookie = key + "=v; expires =" +date.toGMTString();//设置cookie
    }
}

var backend="backend/admin/";

Date.prototype.Format = function(fmt)
{ //author: meizz
    var o = {
        "M+" : this.getMonth()+1,                 //月份
        "d+" : this.getDate(),                    //日
        "h+" : this.getHours(),                   //小时
        "m+" : this.getMinutes(),                 //分
        "s+" : this.getSeconds(),                 //秒
        "q+" : Math.floor((this.getMonth()+3)/3), //季度
        "S"  : this.getMilliseconds()             //毫秒
    };
    if(/(y+)/.test(fmt))
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    for(var k in o)
        if(new RegExp("("+ k +")").test(fmt))
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
    return fmt;
}

var app=new Vue ({
    el:"#app",
    data:{
        webtype:"",//该网页的类型

        login_data:{//登陆数据
            action:"admin_login", //行为描述
            user_id:"",     //账号
            user_passwd:"", //密码 
        },

        login_info:{//自动登陆数据
            action:"admin_auto_login", //行为描述
            user_id:"",     //账号
            user_token:"", //密码 
        },

        user_info:{//登陆以后的用户信息 
            user_name:"",     //姓名
            user_id:"",       //账号
            user_type:"",     //用户类型
            user_token:"",    //token
            class_id:"",      //班级名
        },

        teacher_data:{ //教师信息的增删改查
            user_id:"",
            user_name:"",
            user_passwd:"",    
        },

        passwd:{//用于重置密码
            type:"", //说明是学生重置还是教师重置
            user_id:"",   //说明重置密码的对应id
            new_passwd:"",  //新密码
            passwd_confirm:"", //验证
        },

        change_info:{  //改变信息
            type:"",//说明重置学生还是教师
            user_id:"",//改变的id
            user_name:"",  //姓名
            user_mail:"",  //邮箱
            user_class:"",  //班级
            user_passwd:"", //密码
            is_show:"0",
        },


        classes_info:[], //课程信息
        teacher_info:[], //教师信息
        log:[], //存储返回的全部log
        log_page:"",  //当前页数
        log_all_page:"", //总页数
        show_log:"",
    },

    methods:{
        change_teacher_name_id:function(user_id){
            this.change_info.user_id=user_id;
        },

        change_teacher_name:function(){
            if(isnull(this.change_info.user_name)){
                alert("请输入信息");
                return ;
            }
            this.change_info.type='teacher';
            this.$http.post(backend + "change_teacher_name.php",this.change_info, {emulateJSON:true}).then(function(res){
                //console.log(res.bodyText);
                var dataret = JSON.parse(res.bodyText);
                if (dataret.code == 200)
                {
                    alert("修改成功！");
                    this.change_info.is_show=0;
                    this.change_info.user_name='';
                    this.change_info.user_type='';
                    window.location.reload();
                }
                else
                {
                    alert("修改失败");
                }
            },function(res){
                alert("无法查询(Unknown Reason)");
            });
        },


        change_user_info:function(user_type){
            this.change_info.type=user_type;
            this.$http.post(backend + "change_info.php",this.change_info, {emulateJSON:true}).then(function(res){
                //console.log(res.bodyText);
                var dataret = JSON.parse(res.bodyText);
                if (dataret.code == 200)
                {
                    alert("修改成功！");
                    this.change_info.is_show=0;
                    window.location.reload();
                }
                else
                {
                    alert("修改失败");
                }
            },function(res){
                alert("无法查询(Unknown Reason)");
            });
        },

        delete_change_info:function(){ //关闭后则不展示查询的情况
            this.change_info.is_show=0;
            this.change_info.user_name='';
            this.change_info.user_mail='';
            this.change_info.user_class='';
            this.change_info.user_passwd='';
        },



        search_info:function(user_type){
            if(isnull(this.change_info.user_id)){
                alert("id不能为空");
                return ;
            }
            this.change_info.type=user_type;
            this.$http.post(backend + "search_info.php",this.change_info, {emulateJSON:true}).then(function(res){
                //console.log(res.bodyText);
                var dataret = JSON.parse(res.bodyText);
                if (dataret.code == 200)
                {
                    this.change_info.is_show=1;
                    this.change_info.user_name=dataret.user_name;
                    this.change_info.user_mail=dataret.user_mail;
                    this.change_info.user_class=dataret.user_class;
                    this.change_info.user_passwd=dataret.user_passwd;
                }
                else
                {
                    alert("查询失败");
                }
            },function(res){
                alert("无法查询(Unknown Reason)");
            });
        },



        add:function(){
            if(this.log_page==1){
                alert("已是最新页");
                return ;
            }
            this.log_page-=1;
            if(this.log_page==this.log_all_page)
                this.show_log=this.log.slice((this.log_all_page-1)*10-1,this.log.length);
            else
                this.show_log=this.log.slice((this.log_page-1)*10,this.log_page*10-1);
        },

        reduce:function(){
            if(this.log_page==this.log_all_page){
                alert("已是最后页");
                return ;
            }
            this.log_page+=1;
            if(this.log_page==this.log_all_page)
                this.show_log=this.log.slice((this.log_all_page-1)*10-1,this.log.length);
            else
                this.show_log=this.log.slice((this.log_page-1)*10,this.log_page*10-1);

        },

        delete_log:function(){
            this.$http.get(backend + "delete_log.php",{emulateJSON:true}).then(function(res){
            //console.log(res.bodyText);
            var dataret = JSON.parse(res.bodyText);
            if (dataret.code == 200)
            {
                  alert("日志清理成功！(三天前)");
                  window.location.reload();
            }
            else
            {
                if(dataret.code== 201)
                alert("清理失败(201)");
            }
            },function(res){
                alert("清理失败(Unknown Reason)")
            });
        },

        delete_temp_file:function(){
            this.$http.get(backend + "delete_temp_file.php",{emulateJSON:true}).then(function(res){
            //console.log(res.bodyText);
            var dataret = JSON.parse(res.bodyText);
            if (dataret.code == 200)
            {
                  alert("临时文件删除成功");
                  window.location.reload();
            }
            else
            {
                if(dataret.code== 201)
                alert("删除失败(201)");
            }
            },function(res){
                alert("删除失败(Unknown Reason)")
            });
        },

        log_out:function(){
            cookie.delete("admin_user_token");
            cookie.delete("admin_user_id");
            cookie.delete("admin_user_type");
            alert("退出成功！");
            window.location.href='index.html';
        },


        change_passwd_id:function(user_id){ //用来设置我们修改的是谁的密码
            this.passwd.user_id=user_id;
        },


        change_passwd:function(type){  //type  用来说明修改的密码是学生的还是老师的
                    if(this.passwd.new_passwd!=this.passwd.passwd_confirm){
                        alert("输入的密码不一致！");
                        return ;
                    }
                    if(isnull(this.passwd.new_passwd)){
                        alert("信息不可为空");
                        return ;
                    }
                    this.passwd.type=type;
                    this.$http.post(backend + "change_passwd.php",this.passwd, {emulateJSON:true}).then(function(res){
                    var dataret = JSON.parse(res.bodyText);
                    if (dataret.code == 200)
                    {
                          alert("修改成功!");
                          window.location.reload();
                    }
                    else
                    {
                    
                        alert("修改失败(201)");
                    }
                },function(res){
                        alert("发布失败(Unknown Reason)")
                });
            },



        new_teacher:function(){       //增加教师
            if(isnull(this.teacher_data.user_id)||isnull(this.teacher_data.user_name)||isnull(this.teacher_data.user_passwd)){
                alert("信息不可为空");
                return ;
            }
            this.$http.post(backend + "new_teacher.php",this.teacher_data, {emulateJSON:true}).then(function(res){
            //console.log(res.bodyText);
            var dataret = JSON.parse(res.bodyText);
            if (dataret.code == 200)
            {
                  alert("添加"+this.teacher_data.user_name+'('+this.teacher_data.user_id+')'+"成功!");
                  window.location.reload();
            }
            else
            {
                if(dataret.code== 201)
                alert("发布失败(201)");
            }
            },function(res){
                alert("发布失败(Unknown Reason)")
            });
        },

        login:function(){
            if (isnull(this.login_data.user_id) || isnull(this.login_data.user_passwd))//判断是否输入学号密码
            {
                alert("学号和密码均不能为空！");
                return;
            }
            if(this.login_data.user_type=="null") //判断是否选择身份
            {
                alert("请选择身份！");
                return;
            }
            if (!accept_string(this.login_data.user_passwd) || !accept_string(this.login_data.user_id))//判断是否有非法字符 注：应该为数字以及大小写字母
            {
                alert("学号或密码有非法字符！");
                return;
            }
            this.$http.post(backend + "login.php", this.login_data, {emulateJSON:true}).then(function(res){
                console.log(res.bodyText);
                var dataret = JSON.parse(res.bodyText);
                if (dataret.code == '200')
                {
                    alert("登陆成功！");
                    cookie.set("admin_user_id",this.login_data.user_id);
                    cookie.set("admin_user_token",dataret.token);
                    window.location.href="admin.html?admin_login&user_id="+this.login_data.user_id;
                }
                else
                {
                    if(dataret.code=='201')
                        alert("密码错误");
                    if(dataret.code=='202')
                        alert("当前用户未注册");
                }
            },function(res){
                console.log(res.status);
                alert('登录失败(Unknown Reason)');
            });
        },



        getdate:function() {
 　　         var now = new Date(),
 　　         y = now.getFullYear(),
 　　         m = now.getMonth() + 1,
 　　         d = now.getDate();
 　　         return y + "-" + (m < 10 ? "0" + m : m) + "-" + (d < 10 ? "0" + d : d) + " " + now.toTimeString().substr(0, 8);
        },

        get_log:function(){   //得到日志
            this.$http.get(backend + "get_log.php", {emulateJSON:true}).then(function(res){
                //console.log(res.bodyText);
                var dataret = JSON.parse(res.bodyText);
                if (dataret.code == 200)
                {

                    this.log = dataret.log;
                    this.log_all_page=Math.ceil((dataret.log.length)/10);
                    this.log_page=1;
                     if(this.log_all_page==1){
                            this.show_log= this.log;
                        }
                        else{
                            this.show_log= this.log.slice(0,9);
                        }
                }
                else
                {
                    alert("查询失败");
                }
            },function(res){
                alert("无法查询(Unknown Reason)");
            });
        },

        auto_login:function(){
            this.login_info.user_id=cookie.get("admin_user_id");
            this.login_info.user_token=cookie.get("admin_user_token");
            this.$http.post(backend + "auto_login.php", this.login_info, {emulateJSON:true}).then(function(res){
                var dataret = JSON.parse(res.bodyText);
                if (dataret.code == 200)
                {
                    cookie.delete("admin_user_token");
                    cookie.set("admin_user_token",dataret.user_token);
                    
                    this.classes_info=dataret.classes_info;
                    this.user_info.user_id=cookie.get("user_id");
                    this.user_info.user_name=dataret.user_name;
                    this.user_info.user_type=cookie.get("user_type");
                    this.user_info.user_token=dataret.user_token;
                    this.teacher_info=dataret.teacher_info;
                }
                else if (dataret.code==201)
                    { 
                        alert('token失效，重新登陆');
                        cookie.delete("admin_user_token");
                        cookie.delete("admin_user_id");
                        window.location.href='admin.html';
                    }
            },function(res){
                console.log(res.status);
                alert('自动登陆失败(unknown error)');
            });
        },


        selectclass:function(class_id,class_name){  //转换成删除 日志/删除  临时文件
            this.select_info.user_id=this.user_info.user_id;
            this.select_info.class_id=class_id;
            this.$http.post(backend+"selectclass.php",this.select_info,{emulateJSON:true}).then(function(res){
                var dataret =JSON.parse(res.bodyText);
                if(dataret.code==200)
                {
                    var test=class_id+class_name+'选课成功';
                    alert(test);
                    window.location.reload();
                }
                else if(dataret.code==201)
                {
                    var test=class_id+class_name+'选课失败:已经选过该课程';
                    alert(test);
                }
                else{
                    var test=class_id+class_name+'选课失败:未知原因';
                    alert(test);
                }
            },function(res){
                alert("选课失败(Network Error");
            });
        },

        check_login_status:function(){//判断是否有保存的cookie 有返回true 否则返回false
            //如果没有cookie 我们认为其并未登陆 所以进入登录页面 否则我们根据域名解析出的类型进行初始化
            var cookie_token=cookie.get("admin_user_token");
            if(cookie_token==undefined)
                return true;
            return false;
        },

        web_type_init:function(x){
            if(x){//如果返回值为真 说明未登录
                this.webtype='login';
            }   
            else 
            {
		//说明曾经登陆  如果他没有带后缀 那就说明是有cookie重新开启网页
        var web_url=window.location.href;
		if(web_url.indexOf("?")==-1)
		{
			console.log(web_url);
			window.location.href="admin.html?admin_login&user_id="+cookie.get("admin_user_id");
		}
                web_url=web_url.split("?")[1].split("&")[0];
                this.webtype=web_url;
                this.auto_login();
            }
        },
    },


    created:function(){
        this.web_type_init(this.check_login_status());
    },
})