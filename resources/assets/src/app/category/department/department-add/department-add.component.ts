import {Component, OnInit, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../../services/authSevice';
import {Router} from '@angular/router';
import {DataGlobalService} from '../../../services/data.global.service';
import {CategoryService} from '../../../services/category/category.service';
import {Subscription} from 'rxjs/Subscription';

declare var swal: any;

declare interface Body {
    name: string;
    code: string;
    display_name: string;
    description: string;
}

@Component({
    selector: 'department-add-cmp',
    moduleId: module.id,
    templateUrl: 'department-add.component.html'
})

export class AddDepartmentComponent implements OnInit, OnDestroy {
    errorMsg = '';
    form: FormGroup;
    bodyParam: Body = {
        name: '',
        code: '',
        display_name: '',
        description: '',
    };
    subscription: Subscription;

    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
    }

    constructor(private router: Router,
                private service: CategoryService,
                private dataGlobalService: DataGlobalService) {
    }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-muc/them-phong-ban')) {
            window.history.back();
        }
    }

    addJobStatus(): void {
        if (this.bodyParam.name.trim() !== '' && this.bodyParam.description.trim() !== '') {
            swal({
                title: 'Tạo mới danh mục phòng ban ?',
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
            }).then(r => {
                this.bodyParam.display_name = this.bodyParam.name;
                this.subscription = this.service.create('departments', this.bodyParam).subscribe(
                    res => {
                        if (res.status = 'success') {
                            this.errorMsg = '';
                            setTimeout(function () {
                                swal({
                                    title: 'Thành công!',
                                    text: 'Bạn vừa thêm danh mục phòng ban !',
                                    type: 'success',
                                    confirmButtonText: 'Thoát'
                                }).then(isConfirm => {
                                    if (isConfirm) {
                                        window.history.back();
                                    }
                                });
                            }, 1000);
                        } else {
                            swal(res.message);
                        }
                    },
                    error => {
                        this.dataGlobalService.actionFail(error.error);
                    });

            });

        } else {
            this.errorMsg = 'Thông tin dữ liệu không thể để trống';
        }


    }

    onBack(): void {
        this.ngOnDestroy();
        window.history.back();
    }


}