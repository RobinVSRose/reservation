<?php

namespace Ejoy\Reservation\Controllers\Admin;

use Ejoy\Reservation\Models\Service;

use Ejoy\Reservation\Models\ServiceType;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\MessageBag;

class ServiceController extends Controller
{
    use ModelForm;

    /**
     * 服务列表
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('服务管理');
            $content->description(trans('admin.list'));

            $content->body($this->grid());
        });
    }

    /**
     * 编辑服务
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('服务管理');
            $content->description(trans('admin.edit'));

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * 新建服务
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('服务管理');
            $content->description(trans('admin.create'));

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
        return Admin::grid(Service::class, function (Grid $grid) {
            $channelNameArr=Admin::user()->channelNameArr();
            $typeIdNameArr=ServiceType::query()->whereIn('channel',array_keys($channelNameArr))->pluck('name','id')->toArray();
            $grid->model()->whereIn('channel',array_keys($channelNameArr));
            $grid->disableExport();
            $grid->filter(function($filter) use($channelNameArr,$typeIdNameArr){
                $filter->disableIdFilter();
                $filter->in('channel','渠道')->multipleSelect($channelNameArr);
                $filter->in('type_id','服务类型')->select($typeIdNameArr);
                $filter->like('name', '服务名称');
            });
            $grid->id('ID')->sortable();
            $grid->column('thumb','封面图')->image(null,100,100);
            $grid->name('服务名称');
            $grid->column('type_id','服务类型')->using($typeIdNameArr);
            $grid->column('title','标题')->limit(20);
            $grid->order_by("排序")->editable();
            $grid->updated_at(trans('admin.updated_at'));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Service::class, function (Form $form) {
            $channelNameArr=Admin::user()->channelNameArr();
            $typeIdNameArr=ServiceType::query()->whereIn('channel',array_keys($channelNameArr))->pluck('name','id')->toArray();
            $form->display('id', 'ID');
            $form->select('channel','渠道')->options($channelNameArr);
            $form->select('type_id','服务类型')->options($typeIdNameArr);
            $form->text('name', '服务名称')->rules('required|max:50');
            $form->text('title', '标题')->rules('required|max:50');
            $form->text('duration', '时长');
            $form->image('thumb', '封面图片')->move('upload/services', null)->removable();
            $form->editor('description','描述');
            $form->switch('status','发布状态')->options(config('state.common.switch_flag'))->default(1);
        });
    }
}
