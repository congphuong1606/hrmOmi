import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../services/authSevice';
import {TimeOffService} from '../../services/time-off/TimeOff.service';
import {Router, ActivatedRoute, NavigationEnd} from '@angular/router';
import {TimeOffApprover, TimeOffExcelFile} from '../../models/api/response/TimeOffReponse';
import {DataGlobalService} from '../../services/data.global.service';
import {Subscription} from 'rxjs/Subscription';
import {OvertimeService} from '../../services/overtime.service';
import {CategoryOtherReponse} from '../../models/api/response/CategoriesReponse';
import {CategoryOtherService} from '../../services/category/category-other.service';

declare var $: any;
declare var swal: any;

declare interface Select {
    idSelect: number;
    isSelect: boolean;
}

declare interface Body {
    time_off_ids: number[];
    approved_reason: string;
}


@Component({
    selector: 'statistics-cmp',
    moduleId: module.id,
    templateUrl: 'statistics.component.html'
})


export class RequestStatisticsComponent implements OnInit, OnDestroy {
    otInfor: any;
    approvedStatus = 1;
    defaultType = 'timeoff';
    totalTimeOff = 0;
    timeOffExcelFiles: TimeOffExcelFile[] = [];
    perPage = 15;
    isAll = false;
    showHistory = false;
    titleDropdownDefault = 'Các đơn xin vắng mặt';
    arrayPage: number[] = [];
    lastPage = 1;
    dataRows: TimeOffApprover[] = [];
    userId = -1;
    curentPage = 1;
    form: FormGroup;
    subscription: Subscription;
    subscriptionDataRows: Subscription;
    check = false;
    isImportFile = false;
    loadingImport = true;
    searchValue = '';
    body: Body = {
        time_off_ids: [],
        approved_reason: '',
    };
    selects: Select[] = [];
    importFileTimeOffForm: FormGroup;
    sub: Subscription;
    loadingData = false;
    sub1: Subscription;
    sub4: Subscription;
    projects: CategoryOtherReponse[] = [];
    monthRequest = ((new Date()).getMonth() + 1) + '';
    yearRequest = ((new Date()).getFullYear()) + '';
    statusTimeOff = [false, false, false, false, false, false];
    projectOption = 'all';
    projectId = -1;
    request = {
        search_data: '',
        project_category_id: '',
        status: [],
        approved: '',
        month: this.monthRequest,
        year: this.yearRequest,
    };
    timOffInfor: any;


    constructor(private fb: FormBuilder,
                private http: HttpClient,
                private authService: AuthService,
                private timeOffService: TimeOffService,
                private overtimeService: OvertimeService,
                private categoryOtherService: CategoryOtherService,
                public dataGlobalService: DataGlobalService,
                private route: ActivatedRoute,
                private router: Router) {

    }

    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
        this.subscriptionDataRows !== undefined ? this.subscriptionDataRows.unsubscribe() : console.log(':D');
        this.sub !== undefined ? this.sub.unsubscribe() : console.log(':D');
        this.sub1 !== undefined ? this.sub1.unsubscribe() : console.log(':D');
        this.sub4 !== undefined ? this.sub4.unsubscribe() : console.log(':D');
    }

    ngOnInit() {
        this.subscription = this.router.events.subscribe(val => {
            if (val instanceof NavigationEnd) {
                this.getQueryParamRouter();
                if (this.userId !== -1) {
                    this.getDataRows();
                }
            }
        });
        this.getQueryParamRouter();
        if (!this.dataGlobalService.checkPemisson('/lam-them-gio-va-nghi-phep/thong-ke')) {
            window.history.back();
        } else {
            if (this.userId === -1) {
                this.getUserinfo();
                this.getDataRows();
            }
        }


    }

    checkPemision(path: string): boolean {
        return this.dataGlobalService.checkPemisson(path);
    }


    getQueryParamRouter() {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.curentPage = parseInt(queryParamMap.get('index_page') === null ? '1' : queryParamMap.get('index_page'));
        this.defaultType = queryParamMap.get('default_type') === null ? 'timeoff' : queryParamMap.get('default_type');


        this.request.search_data = queryParamMap.get('search_data') === null ? '' : queryParamMap.get('search_data');
        this.request.project_category_id = queryParamMap.get('project_category_id') === null ? ''
            : queryParamMap.get('project_category_id');
        this.request.approved = queryParamMap.get('approved') === null ? '' : queryParamMap.get('approved');
        this.request.month = queryParamMap.get('month') === null ? this.monthRequest : queryParamMap.get('month');
        this.request.year = queryParamMap.get('year') === null ? this.yearRequest : queryParamMap.get('year');
        this.request.status = queryParamMap.get('status') === null ? [] : this.parseStatus(queryParamMap.get('status'));
    }


    getUserinfo(): void {
        this.userId = this.dataGlobalService.getuserId();
    }

    getDataRows(): void {
        this.request.search_data = $('#search-input-request-statistics').val() === undefined ? ''
            : $('#search-input-request-statistics').val();
        if (this.defaultType === 'timeoff') {
            this.getAllTimeOff();
        } else if (this.defaultType === 'overtime') {
            this.closeFormImport();
            this.getAllOverTime();
        }
    }

    convertDateTIme(time: string): string {
        const ddmmyyyy = time.split(' ')[0];
        const hhmm = time.split(' ')[1];
        const date = ddmmyyyy.split('-')[2] + '-' + ddmmyyyy.split('-')[1] + '-' + ddmmyyyy.split('-')[0];
        return date + ' ' + hhmm.split(':')[0] + ':' + hhmm.split(':')[1];
    }

    showInforTimeOff(row: any) {
        this.timOffInfor = row;
        if (this.timOffInfor !== undefined) {
            $('#inforTimeOffStatics').modal('show');
        }
    }


    // kiểm tra xem có chọn đơn đăng ký nào không (checkbox)
    checkSelect(): boolean {
        let flag = false;
        this.selects.forEach(element => {
            if (element.isSelect) {
                flag = true;
            }
        });
        return flag;
    }

    selectItem(value: number): void {
        this.selects.forEach(element => {
            if (element.idSelect === value) {
                element.isSelect = !element.isSelect;
            }
        });
    }


    selectAllItem(): void {
        this.isAll = !this.isAll;
        if (this.isAll) {
            this.selects.forEach(element => {
                element.isSelect = true;
            });
        } else {
            this.selects.forEach(element => {
                element.isSelect = false;
            });
        }
    }

    checkStatusSelect(value: number): boolean {
        let flag = false;
        this.selects.forEach(element => {
            if (element.idSelect === value) {
                flag = element.isSelect;
            }
        });
        return flag;
    }


    isLoadSuccess(): boolean {
        if (this.check) {
            $('[rel="tooltip"]').tooltip();
        }
        return this.check;
    }


    getRandomString() {
        let text = '';
        const possible = 'abcdefghijklmnopqrstuvwxyz0123456789';

        for (let i = 0; i < 5; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }

        return text;
    }

    onChangPage(index: number): void {
        this.curentPage = index;
        let typeStatus = '';
        if (this.request.status !== []) {
            this.request.status.forEach(item => {
                typeStatus = typeStatus + ';' + item;
            });
        }
        this.router.navigate(['/lam-them-gio-va-nghi-phep/thong-ke'],
            {
                queryParams: {
                    index_page: this.curentPage,
                    default_type: this.defaultType,
                    redirect_id: this.getRandomString(),
                    search_data: this.request.search_data,
                    project_category_id: this.request.project_category_id,
                    status: typeStatus,
                    approved: this.request.approved,
                    month: this.request.month,
                    year: this.request.year,
                }
            });
    }

    refreshPage(): void {
        this.router.navigate(['/lam-them-gio-va-nghi-phep/thong-ke'],
            {
                queryParams: {
                    index_page: this.curentPage,
                    default_type: this.defaultType,
                    redirect_id: this.getRandomString()
                }
            });
        this.isAll = false;
        this.selects.forEach(element => {
            if (element.isSelect) {
                element.isSelect = false;
                this.dataRows = this.dataRows.filter(h => h.id !== element.idSelect);
            }

        });

        this.body.time_off_ids.length = 0;
    }

    refreshData(type: string, title: string) {
        this.titleDropdownDefault = title;
        this.defaultType = type;
        this.curentPage = 1;
        this.getDataRows();
    }

    searchAction() {
        document.getElementById('search-input-request-statistics').blur();
    }

    onSearchChangeValue(value: string) {
        this.searchValue = $('#search-input-request-statistics').val();
    }

    showImportForm() {
        this.createImportFileForm();
        this.isImportFile = true;

    }

    createImportFileForm() {
        this.importFileTimeOffForm = this.fb.group({
            fileTimeOff: [''],
        });
    }

    closeFormImport() {
        this.showHistory = false;
        this.isImportFile = false;
        this.importFileTimeOffForm = undefined;
    }

    importFileTimeOff() {
        const body = {
            file: this.importFileTimeOffForm.get('fileTimeOff').value === null ||
            this.importFileTimeOffForm.get('fileTimeOff').value.length === 0 ? '' :
                this.importFileTimeOffForm.get('fileTimeOff').value[0],
        };
        if (body.file !== '') {
            this.dataGlobalService.disBtnSubmit();
            this.sub = this.timeOffService.importFileTimeOff(body).subscribe(
                data => {
                    swal('Thành công');
                    this.getDataRows();
                    this.dataGlobalService.enableBtnSubmit();

                },
                error => {
                    swal('Thất bại');
                    this.dataGlobalService.enableBtnSubmit();
                    this.dataGlobalService.actionFail(error.error);
                }
            );
        } else {

        }
    }


    showHistoryImport() {
        this.showHistory = !this.showHistory;
        if (this.showHistory) {
            // this.dataGlobalService.disBtnSubmit();
            this.timeOffService.getListImportHistory().subscribe(
                data => {
                    this.timeOffExcelFiles = data.time_off_excel_files as TimeOffExcelFile[];
                    // this.dataGlobalService.enableBtnSubmit();
                }, error => {
                    // this.dataGlobalService.enableBtnSubmit();
                    this.dataGlobalService.actionFail(error.error);
                }
            );
        }

    }

    deleteFileExel(id: number) {
        swal({
            title: 'Xóa file exel này dữ liệu xin nghỉ cũng sẽ bị xóa !',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 500);
                });
            },
            allowOutsideClick: false
        }).then(result => {
            this.timeOffService.deleteDataFileExel(id).subscribe(
                repo => {
                    this.getDataRows();
                    this.timeOffExcelFiles = this.timeOffExcelFiles.filter(file => file.id !== id);
                    swal('Đã Xóa!', 'Bạn đã xóa thành công', 'success');
                },
                error => {
                    this.dataGlobalService.actionFail(error.error);
                });
        });
    }


    reloadData() {
        if ($('#time_option').is(':checked')) {
            this.request.month = $('#option-month option:selected').text().trim();
            this.request.year = $('#option-year option:selected').text().trim();
        } else {
            this.request.month = this.monthRequest;
            this.request.year = this.yearRequest;
        }
        this.request.status.length = 0;
        for (let i of  [0, 1, 2, 3, 4, 5]) {
            if (this.statusTimeOff[i]) {
                this.request.status.push((i + 1));
            }
        }
        if (this.projectOption === 'one') {
            this.request.project_category_id = this.projectId + '';
        } else {
            this.request.project_category_id = '';
        }
        this.getDataRows();
    }

    getAllTimeOff() {
        this.loadingData = false;
        this.subscriptionDataRows = this.timeOffService.getAllTimeOff(this.curentPage, this.request).subscribe(
            data => {
                this.dataRows = (data.times_off as TimeOffApprover[]);
                this.lastPage = (data.pagination.last_page as number);
                this.totalTimeOff = (data.pagination.total as number);
                this.curentPage = (data.pagination.current_page as number);
                this.arrayPage.length = 0;
                for (let i = 1; i <= this.lastPage; i++) {
                    this.arrayPage.push(i);
                }
                this.dataRows.forEach(element => {
                    this.selects.push({idSelect: element.id, isSelect: false});
                });
                this.check = true;
                this.loadingData = true;

            },
            error => {
                this.check = true;
                this.loadingData = true;
                this.dataGlobalService.actionFail(error.message);
            }
        );
    }

    getAllOverTime() {
        this.loadingData = false;
        this.subscriptionDataRows = this.overtimeService.getList(this.curentPage, this.request).subscribe(
            data => {
                this.dataRows = (data.over_times as any[]);
                console.log(this.dataRows);
                this.curentPage = (data.pagination.current_page as number);
                this.lastPage = (data.pagination.last_page as number);
                this.totalTimeOff = (data.pagination.total as number);
                this.arrayPage.length = 0;
                for (let i = 1; i <= this.lastPage; i++) {
                    this.arrayPage.push(i);
                }
                this.dataRows.forEach(element => {
                    this.selects.push({idSelect: element.id, isSelect: false});
                });
                this.check = true;
                this.loadingData = true;

            },
            error => {
                this.check = true;
                this.loadingData = true;
                this.dataGlobalService.actionFail(error.message);
            }
        );

        this.sub4 = this.categoryOtherService.getList('projects').subscribe(
            data => {
                this.projects = data.item_category as CategoryOtherReponse[];
                this.projectId = this.projects[0].id;
            },
            error => {
                this.dataGlobalService.actionFail(error.message);
            }
        );
    }

    showInforOT(id: number) {
        this.sub1 = this.overtimeService.get(id).subscribe(
            data => {
                console.log(data);
                this.otInfor = data.over_time;
                this.sub1 !== undefined ? this.sub1.unsubscribe() : console.log('');
                if (this.otInfor !== undefined) {
                    $('#inforAllOT').modal('show');
                }
            },
            error1 => {
                this.sub1 !== undefined ? this.sub1.unsubscribe() : console.log('');
                this.dataGlobalService.actionFail(error1.error);
            }
        );
    }

    showAdvancedFiltering() {
        $('#advanced-filtering').modal('show');
    }

    changeApprovevalue(approved: number) {
        $('li .trigger-row_approved').on('click', function (event) {
            event.stopPropagation();
        });
        this.approvedStatus = approved;
    }


    changeApprovedStatus(id: number) {
        if (this.approvedStatus === 1) {
            this.approve(id);
        } else if (this.approvedStatus === 2) {
            this.refuse(id);
        }
    }

    refuse(id: number) {
        this.body.time_off_ids.length = 0;
        this.body.approved_reason = '';
        swal({
            title: 'Từ chối',
            text: 'Lưu ý: Bạn sẽ thông báo yêu cầu tạo lại đơn',
            input: 'text',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Từ chối',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 500);
                });
            },
            allowOutsideClick: false
        }).then(result => {
            console.log(result);
            if (result !== '') {
                this.body.time_off_ids.push(id);
                this.body.approved_reason = result;
                if (this.defaultType === 'timeoff') {
                    this.timeOffService.refuseTimeOff(this.body).subscribe(
                        repo => {
                            swal('Thành công', 'Đơn xin đã bị từ chối.', 'success').then(r => {
                                this.dataRows.find(obj => {
                                    return obj.id === id;
                                }).approved = 2;
                            });
                        },
                        error => {
                            this.dataGlobalService.actionFail(error.error);
                        });
                }
                if (this.defaultType === 'overtime') {
                    const bodyOver_time_ids = {
                        over_time_ids: [id],
                        approved_reason: result,
                    };
                    this.overtimeService.refuseOvertime(bodyOver_time_ids).subscribe(
                        repo => {
                            swal('Thành công', 'Đơn xin đã bị từ chối.', 'success').then(r => {
                                this.dataRows.find(obj => {
                                    return obj.id === id;
                                }).approved = 2;
                            });
                        },
                        error => {
                            this.dataGlobalService.actionFail(error.error);
                        });
                }

            } else {
                swal('Thông tin thiếu', 'Chưa nhập thông báo từ chối!', 'error').then(r => {
                    this.refuse(id);
                });
            }
        });
    }

    approve(id: number): void {
        this.body.time_off_ids.length = 0;
        this.body.approved_reason = '';
        this.body.time_off_ids.push(id);
        swal({
            title: 'Duyệt đơn',
            text: 'Các đơn được chọn sẽ được duyệt sau khi bạn đồng ý',
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
            if (this.defaultType === 'timeoff') {
                this.timeOffService.approveTimeOff(this.body).subscribe(
                    repo => {
                        swal('Thành công', 'Đơn đã được duyệt đồng ý', 'success').then(r => {
                            this.dataRows.find(obj => {
                                return obj.id === id;
                            }).approved = 1;
                        });
                    },
                    error => {
                        this.dataGlobalService.actionFail(error.error);
                    });
            }
            if (this.defaultType === 'overtime') {
                const bodyOver_time_ids = {
                    over_time_ids: [id],
                };
                this.overtimeService.approveOvertime(bodyOver_time_ids).subscribe(
                    repo => {
                        swal('Thành công', 'Đơn đã được duyệt đồng ý', 'success').then(r => {
                            this.dataRows.find(obj => {
                                return obj.id === id;
                            }).approved = 1;
                        });
                    },
                    error => {
                        this.dataGlobalService.actionFail(error.error);
                    });
            }

        });

    }

    deleteTimeOff(id: any) {
        swal({
            title: 'Xóa đơn đăng ký này?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(resolve => {

                    setTimeout(r => {
                        resolve();
                    }, 500);
                });
            },
            allowOutsideClick: false
        }).then(
            result => {
                this.timeOffService.delete(id).subscribe(
                    repo => {
                        this.dataRows = this.dataRows.filter(h => h.id !== id);
                        swal('Thành công', 'Đơn xin phép này đã bị xóa', 'success').then(r => {
                        }, f => {
                        });

                    },
                    error => {
                        this.dataGlobalService.actionFail(error.error);
                    });
            },
            cencel => {

            });
    }


    goToUpdateTimeOff(id: number, status: number) {
        this.router.navigate(['/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-vang-mat'], {
            queryParams: {
                id: id,
                type: (status === 1 || status === 2 || status === 3) ? 'di-muon-ve-som' : 'nghi-phep',
            }
        });
    }

    deleteOvertime(row: TimeOffApprover) {
        swal({
            title: 'Xóa đăng ký làm ngoài giờ?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 1000);
                });
            },
            allowOutsideClick: false
        }).then(result => {
            this.overtimeService.delete(row.id).subscribe(
                repo => {
                    this.dataRows = this.dataRows.filter(h => h.id !== row.id);
                    swal('Thành công!', 'Đơn làm ngoài giờ đã được xóa', 'success');
                },
                error => {
                    this.dataGlobalService.actionFail(error.error);
                });
        });
    }

    onBack() {
        window.history.back();
        this.ngOnDestroy();
    }

    goToUpdateOverTime(row: TimeOffApprover) {
        this.router.navigate(['/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-lam-them-gio'], {queryParams: {id: row.id}});
    }

    selectStatus(index: number) {
        this.statusTimeOff[index] = !this.statusTimeOff[index];
    }

    unAdvanced() {
        this.statusTimeOff = [false, false, false, false, false, false];
        this.request = {
            search_data: '',
            project_category_id: '',
            status: [],
            approved: '',
            month: this.monthRequest,
            year: this.yearRequest,
        };
        $('#time_option').prop('checked', false);
        this.projectOption = 'all'
        this.getDataRows();
    }

    setProjectSelected(project: CategoryOtherReponse) {
        this.projectId = project.id;
        this.projectOption = 'one';
    }

    private parseStatus(status1: string) {
        this.request.status.length = 0;
        if (status1.split(';').length > 0) {
            status1.split(';').forEach(item => {
                if (item !== '') {
                    this.request.status.push(parseInt(item));
                }
            });
        }
        return this.request.status;
    }
}
