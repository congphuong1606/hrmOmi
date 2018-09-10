import {Component, OnInit} from '@angular/core';
import {FormGroup} from '@angular/forms';
import {Router, ActivatedRoute, ParamMap} from '@angular/router';
import {DataGlobalService} from '../../services/data.global.service';
import {CategoryService} from '../../services/category/category.service';
import {CategoryOtherReponse, CategoryReponse} from '../../models/api/response/CategoriesReponse';
import {CategoryOtherService} from '../../services/category/category-other.service';

declare var $: any;
declare var swal: any;


@Component({
    selector: 'other-categories-cmp',
    moduleId: module.id,
    templateUrl: 'other-categories.component.html'
})

export class OtherCategoryComponent implements OnInit {
    dataRows: any[] = [];
    BASE_URL = '/danh-muc/danh-sach-trong-danh-muc-khac';
    subUrl = '';
    isloadSuccess = false;
    isTypeOtherCategoryTable = false;
    form: FormGroup;
    typeCategory = {
        category_type: '',
        category_name: ''
    };
    isAdmin = false;
    allowUpdate = false;
    allowAdd = false;

    setupData(): void {
        this.isAdmin = this.dataService.isAdmin();
        this.allowUpdate = this.checkPemision('/danh-muc/cap-nhat-danh-muc-khac'); ///' + this.subUrl
        this.allowAdd = this.checkPemision('/danh-muc/them-danh-muc-khac');

    }

    constructor(private router: Router,
                private service: CategoryService,
                private cateOtherService: CategoryOtherService,
                private route: ActivatedRoute,
                public dataService: DataGlobalService) {

    }


    ngOnInit() {
        this.getQueryParamRouter();
        if (!this.checkPemision(this.BASE_URL)) {
            window.history.back();
        } else {
            this.getDataRow();
        }

    }

    getQueryParamRouter() {
        this.route.paramMap.subscribe((params: ParamMap) => this.subUrl = params.get('category-name'));
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.typeCategory.category_type = queryParamMap.get('category_type') === null ? '' : queryParamMap.get('category_type');
        this.typeCategory.category_name = queryParamMap.get('category_name') === null ? '' : queryParamMap.get('category_name');
        if (this.typeCategory.category_type !== 'specialized_skills'
            && this.typeCategory.category_type !== 'job_positions'
            && this.typeCategory.category_type !== 'working_status') {
            this.isTypeOtherCategoryTable = true;
        } else {
            this.isTypeOtherCategoryTable = false;
        }
        this.setupData();
    }


    checkPemision(path: string): boolean {
        return this.dataService.checkPemisson(path);
    }


    isLoadSuccess(): boolean {
        if (this.isloadSuccess) {
            $('[rel="tooltip"]').tooltip();
        }
        return this.isloadSuccess;
    }

    getDataRow(): void {
        if (this.isTypeOtherCategoryTable) {
            if (this.typeCategory.category_type !== 'reasons') {
                this.cateOtherService.getList(this.typeCategory.category_type).subscribe(
                    repo => {
                        this.isloadSuccess = true;
                        this.dataRows = repo.item_category as CategoryOtherReponse[];
                    },
                    error => {
                        this.dataService.actionFail(error.message);
                    });


            } else {
                this.cateOtherService.getListReasonsCategory().subscribe(
                    repo => {
                        this.isloadSuccess = true;
                        this.dataRows = repo.late_reasons as any[];
                    },
                    error => {
                        this.dataService.actionFail(error.error);
                    });
            }
        } else {


            this.service.getList(this.typeCategory.category_type).subscribe(
                repo => {
                    this.isloadSuccess = true;
                    this.dataRows = (Object.getOwnPropertyDescriptor(repo, this.typeCategory.category_type).value as CategoryReponse[]);
                },
                error => {
                    this.dataService.actionFail(error.error);
                });


        }

    }

    updateDepartment(data: any): void {
        if (this.typeCategory.category_type === 'reasons') {
            this.router.navigate(['../danh-muc/cap-nhat-danh-muc-khac/', this.subUrl], {
                queryParams: {
                    category_type: this.typeCategory.category_type,
                    category_name: this.typeCategory.category_name,
                    id: data.id,
                    name: data.name,
                    description: data.description,
                    start_morning: data.start_morning,
                    end_morning: data.end_morning,
                    start_afternoon: data.start_afternoon,
                    end_afternoon: data.end_afternoon,
                }
            });
        } else {
            this.router.navigate(['../danh-muc/cap-nhat-danh-muc-khac/', this.subUrl], {
                queryParams: {
                    category_type: this.typeCategory.category_type,
                    category_name: this.typeCategory.category_name,
                    id: data.id,
                    name: data.name,
                    description: data.description,

                }
            });
        }

    }

    addDepartment(): void {
        this.router.navigate(['../danh-muc/them-danh-muc-khac/', this.subUrl], {queryParams: this.typeCategory});
    }

    onBack(): void {
        window.history.back();
    }

    deleteDepartment(data: any): void {
        swal({
            title: 'Xác nhận xóa?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 500);
                });
            },

        }).then(result => {
            if (this.isTypeOtherCategoryTable) {
                if (this.typeCategory.category_type === 'reasons') {
                    this.cateOtherService.deleteReasonCateGory(data.id).subscribe(
                        repo => {
                            this.deleteSuccess(data);
                        },
                        error => {
                            this.dataService.actionFail(error.message);
                        });
                } else {
                    this.cateOtherService.delete(data.id).subscribe(
                        repo => {
                            this.deleteSuccess(data);
                        },
                        error => {
                            this.dataService.actionFail(error.message);
                        });
                }

            } else {
                this.service.delete(this.typeCategory.category_type, data.id).subscribe(
                    repo => {
                        this.deleteSuccess(data);
                    },
                    error => {
                        this.dataService.actionFail(error.error);
                    });
            }


        });
    }


    private deleteSuccess(data) {
        swal('Đã Xóa!', 'Bạn đã xóa thành công', 'success');
        this.dataRows = this.dataRows.filter(h => h !== data);
    }
}

