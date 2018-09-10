import {Component, OnDestroy, OnInit} from '@angular/core';
import {Router, ActivatedRoute, ParamMap, NavigationEnd} from '@angular/router';
import {RoleService} from '../../../../services/role.service';
import {Subscription} from 'rxjs/Subscription';
import {DataGlobalService} from '../../../../services/data.global.service';
import {UserCourseRepose} from '../../../../models/api/response/CourseRepose';
import {CategoryOtherReponse, CategoryReponse} from '../../../../models/api/response/CategoriesReponse';
import {CategoryService} from '../../../../services/category/category.service';
import {CourseService} from '../../../../services/course/course.service';

declare var swal: any;
declare var $: any;


@Component({
    selector: 'add-users-role-cmp',
    moduleId: module.id,
    templateUrl: 'add-users-role.component.html'
})

export class AddUserRoleComponent implements OnInit, OnDestroy {

    roleId: number;
    roleName = '';
    departmentsName = 'Tất cả phòng ban';
    officePositionName = 'Tất cả chức danh';
    department_id = '';
    job_position_id = '';
    search_value = '';
    userIds: number[] = [];
    dataRows: UserCourseRepose[] = [];
    sub1: Subscription;
    sub2: Subscription;
    sub3: Subscription;
    subscription: Subscription;
    check1 = false;
    check2 = false;
    check3 = false;
    refreshListUser = false;
    officePositions: CategoryReponse[] = [];
    departments: CategoryReponse[] = [];
    isAll = false;

    ngOnDestroy(): void {
        this.sub2 !== undefined ? this.sub2.unsubscribe() : console.log('');
        this.sub1 !== undefined ? this.sub1.unsubscribe() : console.log('');
        this.sub3 !== undefined ? this.sub3.unsubscribe() : console.log('out');
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log('out');
    }

    constructor(private router: Router,
                private route: ActivatedRoute,
                private dataGlobalService: DataGlobalService,
                private categoryService: CategoryService,
                private courseService: CourseService,
                private roleService: RoleService) {
    }

    ngOnInit() {
        this.subscription = this.route.paramMap.subscribe((params: ParamMap) => {
            this.roleId = parseInt(params.get('id'));
            this.roleName = params.get('role-name');
        });
        this.router.events.subscribe(val => {
            if (val instanceof NavigationEnd) {
                this.getQueryParamRouter();
                if (this.roleId !== undefined) {
                    this.getData();
                }
            }
        });
        this.getQueryParamRouter();
        if (this.roleId !== undefined) {
            this.getData();
        }
    }

    getQueryParamRouter() {
        const queryParamMap = this.route.snapshot.queryParamMap;
        // this.curentPage = parseInt(queryParamMap.get('index_page') === null ? '1' : queryParamMap.get('index_page'));
    }


    onBack(): void {
        this.ngOnDestroy();
        window.history.back();

    }

    private getData() {
        this.sub1 = this.categoryService.getList('positions').subscribe(
            repo => {
                this.check1 = true;
                this.officePositions = (repo.positions as CategoryReponse[]);
            },
            error => {
                this.check1 = true;
                this.dataGlobalService.actionFail(error.error);
            });
        this.sub2 = this.categoryService.getList('departments').subscribe(
            repo => {
                this.check2 = true;
                this.departments = (repo.departments as CategoryReponse[]);
            },
            error => {
                this.check2 = true;
                this.dataGlobalService.actionFail(error.error);
            });
        this.getListEmployees();

    }

    private initToolTip() {
        $('[rel="tooltip"]').tooltip();
    }

    private onSearchValueChange(searchValue: string) {
        this.search_value = searchValue.trim();
    }

    private getListEmployees(): void {
        this.refreshListUser = false;
        this.dataRows.length = 0;
        this.sub3 = this.courseService.searchUser(this.department_id,
            this.job_position_id, this.search_value, this.roleId).subscribe(
            repo => {
                this.check3 = true;
                this.refreshListUser = true;
                console.log(repo);
                this.dataRows = repo.users as UserCourseRepose[];
                this.setValuecheckBox();

            },
            error => {
                this.check3 = true;
                this.refreshListUser = true;
                this.dataGlobalService.actionFail(error.message);
            }
        );
    }

    private setDataOption(type: string, item: any): void {
        const id = item !== '' ? item.id + '' : '';
        if (type === 'departments') {
            this.departmentsName = item !== '' ? item.name : 'Tất cả phòng ban';
            this.department_id = id;
        }
        if (type === 'positions') {
            this.officePositionName = item !== '' ? item.name : 'Tất cả chức danh';
            this.job_position_id = id;
        }
        this.getListEmployees();
    }

    private addRoleForUsers(): void {
        console.log(this.userIds);
        this.roleService.addRoleForUser(this.userIds, this.roleId).subscribe(
            res => {
                swal({
                    title: 'Thành công!',
                    text: 'Đã cập nhật danh sách user cho nhóm quyền',
                    type: 'success',
                    confirmButtonText: 'Thoát',
                    allowOutsideClick: false,
                }).then(isConfirm => {
                    if (isConfirm) {
                        window.history.back();
                    }
                });
            },
            error => {
                this.dataGlobalService.actionFail(error.error);
            });
    }

    private selectItemEmployee(row: UserCourseRepose): void {
        row.is_selected = !row.is_selected;
        if (row.is_selected) {
            this.userIds.push(row.id);
        } else {
            this.userIds = this.userIds.filter(h => h !== row.id);
        }
    }

    private selectAllItem(): void {
        this.isAll = !this.isAll;
        this.dataRows.forEach(item => {
            item.is_selected = this.isAll ? true : false;
            this.isAll ? this.userIds.push(item.id) : this.userIds = this.userIds.filter(h => h !== item.id);
        });

    }


    private setValuecheckBox() {
        this.isAll = true;
        this.dataRows.forEach(item => {
            const id = this.userIds.find(userId => {
                return userId === item.id;
            });
            if (id !== undefined) {
                item.is_selected = true;
            }
            if (id === undefined) {
                item.is_selected = false;
                this.isAll = false;
            }
        });
    }
}
