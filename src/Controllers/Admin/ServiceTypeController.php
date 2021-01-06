<?php

namespace Ejoy\Reservation\Controllers\Admin;

use Ejoy\Reservation\Models\ServiceType;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;

class ServiceTypeController extends Controller
{
    use ModelForm;
    /**
     * Index interface.
     *
     * @return Content
     */

    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('服务类型');
            $content->description('列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('服务类型');
            $content->description('编辑');

            $content->body($this->form($id)->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('服务类型');
            $content->description('创建');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(ServiceType::class, function (Grid $grid) {
            $channelNameArr=Admin::user()->channelNameArr();
            $grid->model()->whereIn('channel',array_keys($channelNameArr));
            $grid->disableExport()->disableRowSelector()->disableActions();
            $grid->model()->orderByDesc('id');
            $grid->id('ID')->sortable();
            $grid->column('thumb','封面图')->image(null,100,100);
            $grid->name('名称');
            $grid->column('channel','渠道')->using($channelNameArr);
            $grid->updated_at('更新时间');
            $grid->column('status','状态')->switch(config('state.common.switch_flag'))->default(1);
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id=null)
    {
        return Admin::form(ServiceType::class, function (Form $form) use ($id) {
            $channelNameArr=Admin::user()->channelNameArr();
            $form->display('id', 'ID');
            $form->select('channel','渠道')->options($channelNameArr);
            $form->text('name','名称')->rules("required");
            $form->image('thumb','封面图')->move('upload/service')->removable();
            $form->switch('status','状态')->options(config('state.common.switch_flag'));
            $form->display('created_at', '创建时间');
            $form->display('updated_at', '更新时间');
        });
    }
}
