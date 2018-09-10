import {Component, OnInit, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../../services/authSevice';
import {Router} from '@angular/router';
import {RoleService} from '../../../services/role.service';
import {Subscription} from 'rxjs/Subscription';
import {DataGlobalService} from '../../../services/data.global.service';


declare var swal: any;

declare interface Body {
    name: string;
    display_name: string;
    description: string;
}

@Component({
    selector: 'role-add-cmp',
    moduleId: module.id,
    templateUrl: 'role-add.component.html'
})

export class AddRoleComponent implements OnInit, OnDestroy {
    errorMsg: String = '';
    form: FormGroup
    subscription: Subscription;
    bodyParam: Body = {
        name: '',
        display_name: '',
        description: '',
    };

    constructor(private router: Router,
                private dataGlobalService: DataGlobalService,
                private roleService: RoleService) {
    }


    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
    }

    ngOnInit() {
    }

    addRole(): void {
        if (this.bodyParam.name.trim() !== '' && this.bodyParam.description.trim() !== '') {
            this.bodyParam.display_name = this.bodyParam.name;
            swal({
                title: 'Tạo mới nhóm quyền ?',
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
            }).then(a => {
                this.roleService.createRole(this.bodyParam).subscribe(
                    res => {
                        if (res.status = 'success') {
                            this.errorMsg = '';
                            setTimeout(function () {
                                swal({
                                    title: 'Thành công!',
                                    text: 'Bạn vừa thêm vai trò ' + res.role.name + ' !',
                                    type: 'success',
                                    confirmButtonText: 'Thoát'
                                }).then(isConfirm => {
                                    if (isConfirm) {
                                        window.history.back();
                                    }
                                });
                            }, 1000);
                        } else {
                            this.errorMsg = 'có lỗi ';
                        }
                        this.ngOnDestroy();
                    },
                    error => {
                        this.dataGlobalService.actionFail(error.error);
                        this.ngOnDestroy();
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