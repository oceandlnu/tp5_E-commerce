<?php

/**
 * Created by PhpStorm.
 * User: ocean
 * Date: 18-1-5
 * Time: 下午2:37
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Image;
use app\admin\model\Admin;
use app\admin\model\User;
use app\admin\model\Cate;
use app\admin\model\Pro;
use app\admin\model\Album;

class Index extends Controller
{
    protected $thumbSizes;//规定缩略图尺寸
    protected $imagesPath;//上传商品图片的保存路径
    protected $facePath;//头像的保存路径
    protected $logo;//logo的图片
    protected $logoPath;//logo的路径
    protected $text;//默认文字水印内容
    protected $fonts;//字体文件
    protected $fontsPath;//字体文件保存的路径

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->thumbSizes = [800, 350, 220, 50];
        $this->imagesPath = "public/static/index/images/uploads";
        $this->facePath = "public/static/index/images/userFaces";
        $this->logo = "logo.png";
        $this->logoPath = "public/static/index/images";
        $this->text = "Ocean";
        $this->fonts = ["SIMYOU.TTF", "simsun.ttf"];
        $this->fontsPath = "public/static/admin/fonts";
    }

    /**
     * 检查是否登录
     */
    public function check()
    {
        if (Admin::check_is_login()) {
            $res = 'index';
        } elseif (!Request::instance()->has('verify', 'post')) {
            $res = 'login';
        } else {
            $data = Request::instance()->post();
            if (Admin::check_login($data)) {
                $res = 'index';
            } else {
                $res = 'login';
            }
        }
        $this->success('', $res, '', 1);
    }

    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        if (Admin::check_is_login()) {
            return $this->fetch();
        } else {
            alertMes("请先登录");
            $this->success('', 'login', '', 1);
        }
    }

    /**
     * 登录
     * @return mixed
     */
    public function login()
    {
        $cates = Cate::all();
        $this->assign('cates', $cates);
        $this->assign('title', '管理员登录');
        return $this->fetch();
    }

    /**
     * 退出
     */
    public function logout()
    {
        Admin::logout();
        $this->success('', 'login', '', 1);
    }

    /**
     * main模块
     * @return mixed
     */
    public function main()
    {
        return $this->fetch();
    }

    /**
     * 添加管理员
     * @return mixed
     */
    public function addAdmin()
    {
        return $this->fetch();
    }

    /**
     * 显示管理员列表
     * @return mixed
     */
    public function listAdmin()
    {
        $request = Request::instance();
        if (!$request->has('page', 'get')) {
            $page = 1;
        } else {
            $page = $request->get('page');
        }
        // 查询状态为1的用户数据 并且每页显示size条数据
        $url = $request->baseUrl();
        $size = 2;
        $admin = new Admin();
        $rows = $admin->paginate($size);
        $total = $rows->total();
        $totalPage = ceil($total / $size);
        $showPage = showPage($page, $totalPage, $url);
        // 把分页数据赋值给模板变量rows
        $this->assign('rows', $rows);
        $this->assign('size', $size);
        $this->assign('total', $total);
        $this->assign('showPage', $showPage);
        // 渲染模板输出
        return $this->fetch();
    }

    /**
     * 编辑管理员
     */
    public function editAdmin()
    {
        $request = Request::instance();
        $id = $request->get('id');
        $row = Admin::get($id);
        $this->assign('row', $row);
        $this->assign('id', $row['id']);
        return $this->fetch();
    }

    /**
     * 删除管理员
     */
    public function delAdmin()
    {
        $request = Request::instance();
        $id = $request->get('id');
        $res = Admin::destroy($id);
        if ($res) {
            alertMes("删除成功");
        } else {
            alertMes("删除失败");
        }
        $this->success('', 'listAdmin');
    }

    /**
     * 执行增加管理员操作
     */
    public function doAddAdmin()
    {
        $request = Request::instance();
        $data = $request->post();
        if (!$data['username']) {
            alertMes("请输入管理员名称");
            $this->success('', 'addAdmin');
        } elseif (!$data['password']) {
            alertMes("请输入密码");
            $this->success('', 'addAdmin');
        } else {
            $data['password'] = md5($data['password']);
            $admin = new Admin($data);
            $admin->allowField(true)->save();
            $this->success('', 'listAdmin');
        }
    }

    /**
     * 更新管理员操作
     */
    public function doEditAdmin()
    {
        $request = Request::instance();
        $data = $request->post();
        $data['password'] = md5($data['password']);
        $id = $request->get('id');
        $admin = new Admin;
        $admin->allowField(true)->save($data, ['id' => $id]);
        $this->success('', 'listAdmin');
    }

    /**
     * 添加用户
     * @return mixed
     */
    public function addUser()
    {
        return $this->fetch();
    }

    /**
     * 显示用户列表
     * @return mixed
     */
    public function listUser()
    {
        $request = Request::instance();
        if (!$request->has('page', 'get')) {
            $page = 1;
        } else {
            $page = $request->get('page');
        }

        // 查询状态为1的用户数据 并且每页显示size条数据
        $url = $request->baseUrl();
        $size = 2;
        $user = new User();
        $rows = $user->paginate($size);
        $total = $rows->total();
        $totalPage = ceil($total / $size);
        $showPage = showPage($page, $totalPage, $url);
        // 把分页数据赋值给模板变量rows
        $this->assign('rows', $rows);
        $this->assign('size', $size);
        $this->assign('total', $total);
        $this->assign('showPage', $showPage);
        // 渲染模板输出
        return $this->fetch();
    }

    public function editUser()
    {
        $request = Request::instance();
        $id = $request->get('id');
        $row = User::get($id);
        $this->assign('row', $row);
        $this->assign('id', $row['id']);
        return $this->fetch();
    }

    public function delUser()
    {
        $request = Request::instance();
        $id = $request->get('id');
        $faceImg = User::where('id', $id)->value('face');
        if ($faceImg) {
            if (file_exists(ROOT_PATH . $this->facePath . DS . $faceImg)) {
                unlink(ROOT_PATH . $this->facePath . DS . $faceImg);
            }
        }
        $res = User::destroy($id);
        if ($res) {
            alertMes("删除成功");
        } else {
            alertMes("删除失败");
        }
        $this->success('', 'listUser');
    }

    public function doAddUser()
    {
        $request = Request::instance();
        $data = $request->post();
        if ($data['password'] !== $data['confirmPwd']) {
            alertMes("两次输入密码不一致，请重新输入");
            $this->success('', 'addUser');
        }
        $file = $request->file('face');
        if ($file) {
            $vali = ['ext' => 'jpg,png,gif'];
            $rule = 'uniqid';
            $info = $file->rule($rule)->validate($vali)->move(ROOT_PATH . $this->facePath);
            if ($info) {
                $data['face'] = $info->getSaveName();
            } else {
                echo $info->getError();
            }
            $data['regTime'] = time();
            $data['password'] = md5($data['password']);
            $admin = new User($data);
            $admin->allowField(true)->save();
            alertMes("添加成功");
            $this->success('', 'listUser');
        } else {
            alertMes("请上传头像");
            $this->success('', 'addUser');
        }
    }

    public function doEditUser()
    {
        $request = Request::instance();
        $data = $request->post();
        $data['password'] = md5($data['password']);
        $id = $request->get('id');
        $admin = new User();
        $admin->allowField(true)->save($data, ['id' => $id]);
        $this->success('', 'listUser');
    }

    public function addCate()
    {
        return $this->fetch();

    }

    public function listCate()
    {
        $request = Request::instance();
        if (!$request->has('page', 'get')) {
            $page = 1;
        } else {
            $page = $request->get('page');
        }

        // 查询状态为1的用户数据 并且每页显示size条数据
        $url = $request->baseUrl();
        $size = 2;
        $cate = new Cate();
        $rows = $cate->paginate($size);
        $total = $rows->total();
        $totalPage = ceil($total / $size);
        $showPage = showPage($page, $totalPage, $url);
        // 把分页数据赋值给模板变量rows
        $this->assign('rows', $rows);
        $this->assign('size', $size);
        $this->assign('total', $total);
        $this->assign('showPage', $showPage);
        // 渲染模板输出
        return $this->fetch();
    }

    public function editCate()
    {
        $request = Request::instance();
        $id = $request->get('id');
        $row = Cate::get($id);
        $this->assign('row', $row);
        $this->assign('id', $row['id']);
        return $this->fetch();
    }

    public function delCate()
    {
        $request = Request::instance();
        $id = $request->get('id');
        if (Pro::all(['cId' => $id])) {
            alertMes("请先删除该分类下的商品");
        } else {
            $res = Cate::destroy($id);
            if ($res) {
                alertMes("删除成功");
            } else {
                alertMes("删除失败");
            }
        }
        $this->success('', 'listCate');
    }

    public function doAddCate()
    {
        $request = Request::instance();
        $data = $request->post();
        $admin = new Cate($data);
        $admin->allowField(true)->save();
        $this->success('', 'listCate');
    }

    public function doEditCate()
    {
        $request = Request::instance();
        $data = $request->post();
        $id = $request->get('id');
        $admin = new Cate();
        $admin->allowField(true)->save($data, ['id' => $id]);
        $this->success('', 'listCate');
    }

    public function addPro()
    {
        $rows = Cate::all();
        if (!$rows) {
            alertMes("没有分类，请先添加分类");
            $this->success('', 'addCate');
        }
        $this->assign('rows', $rows);
        return $this->fetch();
    }

    public function listPro()
    {
        $request = Request::instance();
        $page = $request->has('page', 'get') ? $request->get('page') : 1;
        $keywords = $request->has('keywords', 'get') ? $request->get('keywords') : null;
        $order = $request->has('order', 'get') ? $request->get('order') : null;
        // 查询状态为1的用户数据 并且每页显示size条数据
        $url = $request->baseUrl();
        $size = 2;
//        $pro = new Pro();
//        $rows = $pro->paginate($size);
        $str = "p.id,p.pName,p.pSn,p.pNum,p.mPrice,p.iPrice,p.pDesc,p.pubTime,p.isShow,p.isHot,c.cName";
        $rows = Db::name('pro')->field($str)->alias('p')->where("p.pName like '%{$keywords}%'")->order($order)->join('shop_cate c', 'p.cId=c.id')->paginate($size);
//        dump($rows);
        $total = $rows->total();
        $totalPage = ceil($total / $size);
        $showPage = showPage($page, $totalPage, $url, "keywords={$keywords}&order={$order}");
        // 把分页数据赋值给模板变量rows
        $this->assign('rows', $rows);
        $this->assign('size', $size);
        $this->assign('total', $total);
        $this->assign('showPage', $showPage);
        // 渲染模板输出
        return $this->fetch();
    }

    public function editPro()
    {
        $request = Request::instance();
        $id = $request->get('id');
        $proInfo = Pro::get($id);
        $rows = Cate::all();
        $this->assign('proInfo', $proInfo);
        $this->assign('rows', $rows);
        $this->assign('id', $proInfo['id']);
        return $this->fetch();
    }

    public function delPro()
    {
        $request = Request::instance();
        $id = $request->get('id');
        $resPro = Pro::destroy($id);
        $proImgs = Album::all(['pid' => $id]);
        foreach ($proImgs as $proImg) {
            if (file_exists(ROOT_PATH . $this->imagesPath . DS . $proImg['albumPath'])) {
                unlink(ROOT_PATH . $this->imagesPath . DS . $proImg['albumPath']);
            }
            foreach ($this->thumbSizes as $thumbSize) {
                if (file_exists(ROOT_PATH . $this->imagesPath . DS . "images_" . $thumbSize . DS . $proImg['albumPath'])) {
                    unlink(ROOT_PATH . $this->imagesPath . DS . "images_" . $thumbSize . DS . $proImg['albumPath']);
                }
            }
        }
        $resAlbum = Album::destroy(['pid' => $id]);
        if ($resPro && $resAlbum) {
            alertMes("删除成功");
        } else {
            alertMes("删除失败");
        }
        $this->success('', 'listPro');
    }


    public function doAddPro()
    {
        $request = Request::instance();
        $data = $request->post();
        $data['pubTime'] = time();
        $files = $request->file('thumbs');
        if ($files) {
            if ($data['pName']) {
                $pro = new Pro($data);
                $pro->allowField(true)->save();
                $albumData['pid'] = $pro->id;
                $vali = ['ext' => 'jpg,png,gif'];
                $rule = 'uniqid';
                foreach ($files as $file) {
                    $info = $file->rule($rule)->validate($vali)->move(ROOT_PATH . $this->imagesPath);
                    if ($info) {
                        $albumData['albumPath'] = $info->getSaveName();
                        $image = Image::open(ROOT_PATH . $this->imagesPath . DS . $albumData['albumPath']);
                        foreach ($this->thumbSizes as $thumbSize) {
                            $image->thumb($thumbSize, $thumbSize, Image::THUMB_FIXED)->save(ROOT_PATH . $this->imagesPath . DS . "images_" . $thumbSize . DS . $albumData['albumPath']);
                        }
                    } else {
                        echo $info->getError();
                    }
                    $album = new Album($albumData);
                    $album->allowField(true)->save();
                }
                alertMes("添加成功");
                $this->success('', 'listPro');
            } else {
                alertMes("请填写完整的商品信息");
                $this->success('', 'addPro');
            }
        } else {
            alertMes("请上传商品图片");
            $this->success('', 'addPro');
        }
    }

    public function doEditPro()
    {
        $request = Request::instance();
        $data = $request->post();
        $id = $request->get('id');
        $pro = new Pro();
        $pro->allowField(true)->save($data, ['id' => $id]);
        $albumData['pid'] = $id;
        $files = $request->file('thumbs');
        if ($files) {
            $vali = ['ext' => 'jpg,png,gif'];
            $rule = 'uniqid';
            foreach ($files as $file) {
                $info = $file->rule($rule)->validate($vali)->move(ROOT_PATH . $this->imagesPath);
                if ($info) {
                    $albumData['albumPath'] = $info->getSaveName();
                    $image = Image::open(ROOT_PATH . $this->imagesPath . DS . $albumData['albumPath']);
                    foreach ($this->thumbSizes as $thumbSize) {
                        $image->thumb($thumbSize, $thumbSize, Image::THUMB_FIXED)->save(ROOT_PATH . $this->imagesPath . DS . "images_" . $thumbSize . DS . $albumData['albumPath']);
                    }
                } else {
                    echo $info->getError();
                }
                $album = new Album($albumData);
                $album->allowField(true)->save();
            }
            $this->success('修改成功', 'listPro');
        }
        $this->success('修改成功', 'listPro');
    }

    public function listProImages()
    {
        $request = Request::instance();
        $page = $request->has('page', 'get') ? $request->get('page') : 1;
        $keywords = $request->has('keywords', 'get') ? $request->get('keywords') : null;
        $order = $request->has('order', 'get') ? $request->get('order') : null;
        // 查询状态为1的用户数据 并且每页显示size条数据
        $url = $request->baseUrl();
        $size = 2;
        $admin = new Pro();
        $rows = $admin->where("pName like '%{$keywords}%'")->order($order)->paginate($size);
        $total = $rows->total();
        $totalPage = ceil($total / $size);
        $showPage = showPage($page, $totalPage, $url, "keywords={$keywords}&order={$order}");
        // 把分页数据赋值给模板变量rows
        $this->assign('rows', $rows);
        $this->assign('size', $size);
        $this->assign('total', $total);
        $this->assign('showPage', $showPage);
        // 渲染模板输出
        return $this->fetch();
    }

    public function addWater()
    {
        $request = Request::instance();
        $data = $request->get();
        $images = getImgByProId($data['id']);
        if ($data['act'] == 'waterText') {
            $font_size = 20;
            $font_color = "#ffffff";
            foreach ($images as $image) {
                $img = Image::open(ROOT_PATH . $this->imagesPath . DS . $image);
                $img->text($this->text, ROOT_PATH . $this->fontsPath . DS . $this->fonts[0], $font_size, $font_color)->save(ROOT_PATH . $this->imagesPath . DS . $image);
            }
        } elseif ($data['act'] == 'waterPic') {
            foreach ($images as $image) {
                $img = Image::open(ROOT_PATH . $this->imagesPath . DS . $image);
                $img->water(ROOT_PATH . $this->logoPath . DS . $this->logo, Image::WATER_NORTHWEST)->save(ROOT_PATH . $this->imagesPath . DS . $image);
            }
        }
        alertMes("添加成功");
        $this->success('', 'listProImages', '', 1);
    }

    public function test()
    {
        return $this->fetch();
    }
}