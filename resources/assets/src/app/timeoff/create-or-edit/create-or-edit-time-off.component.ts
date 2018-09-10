import {Component, OnInit, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../services/authSevice';
import {Router, ActivatedRoute} from '@angular/router';
import {TimeOffService} from '../../services/time-off/TimeOff.service';
import {DataGlobalService} from '../../services/data.global.service';
import * as constants from '../../constants';
import {ShowAnTimeOff} from '../../models/api/response/TimeOffReponse';
import {Subscription} from 'rxjs/Subscription';
import * as  moment from 'moment';


declare var $: any;
declare var swal: any;

declare interface PramRouter {
    id: string;
    type: string;
}


@Component({
    selector: 'create-or-edit-time-off-cmp',
    moduleId: module.id,
    templateUrl: 'create-or-edit-time-off.component.html'
})


export class CreateOrEditTimeOffComponent implements OnInit, OnDestroy {
    showAnTimOff: ShowAnTimeOff = new ShowAnTimeOff();
    endDateTimeValue: string;
    startDateTimeValue: string;
    endTimeValue: string;
    startTimeValue: string;
    dateValue: string;
    startValue = '';
    endValue = '';
    detailed_reason = '';
    reason = '';
    backup_person = '';
    /*  project_manger = '';
      team_leader = '';*/
    listReason = constants.LIST_REASONS;
    type = '';
    errorMsg = '';
    employeeId: number;
    status = 0;
    range = -1;
    range_unit = -1;
    numDate = Date.now();
    curentDate = '';
    fromHour = '';
    tohour = '';
    type1 = false;
    type2 = false;
    type3 = false;
    type4 = false;
    type5 = false;
    type6 = false;

    formTimeOffLate: FormGroup;
    formTimeOffLate2: FormGroup;
    isSuccess = false;


    pramRouter: PramRouter = {
        id: '',
        type: '',
    };
    sub: Subscription;
    sub1: Subscription;
    loadDataTimeOff = true;
    isDeleteTimeOff = false;
    focusOut1 = false;
    focusOut2 = false;
    focusOut3 = false;
    name1 = '';
    name2 = '';
    name3 = '';
    listApproverEmail = '';
    sub2: Subscription;
    dayOffAccumulatedOtTemp = 0;
    dayOffAccumulatedPermitTemp = 0;


    constructor(private fb: FormBuilder,
                private http: HttpClient,
                private route: ActivatedRoute,
                private authService: AuthService,
                private timeOffService: TimeOffService,
                public dataGlobalService: DataGlobalService,
                private router: Router) {

    }

    ngOnDestroy(): void {
        this.sub !== undefined ? this.sub.unsubscribe() : console.log('');
        this.sub1 !== undefined ? this.sub1.unsubscribe() : console.log('');
        this.sub2 !== undefined ? this.sub2.unsubscribe() : console.log('');
    }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-vang-mat')) {
            window.history.back();
        } else {
            this.getApproverEmail();
            this.getPramRouter();
        }
    }

    getApproverEmail(): void {
        this.sub2 = this.timeOffService.getListApproverEmail().subscribe(
            data => {
                if (data.status === 'success') {
                    this.dayOffAccumulatedPermitTemp = data.result.day_off_accumulated_permit_temp;
                    this.dayOffAccumulatedOtTemp = data.result.day_off_accumulated_ot_temp;
                    const managerEmail = data.result.project_manager.email !== null ? data.result.project_manager.email + '' : '';
                    const leaserEmail = data.result.team_leader.email !== null ? data.result.team_leader.email + '' : '';
                    if (managerEmail.trim() !== '' && leaserEmail.trim() !== '') {
                        this.listApproverEmail = leaserEmail + ',' + managerEmail;
                    } else {
                        this.listApproverEmail = leaserEmail + '' + managerEmail;
                    }
                }
            },
            error => {
                console.log(error.error);
            }
        );
    }

    getPramRouter(): void {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.pramRouter.id = queryParamMap.get('id') === null ? '' : queryParamMap.get('id');
        this.pramRouter.type = queryParamMap.get('type') === null ? '' : queryParamMap.get('type');
        this.pramRouter.id !== '' ? this.getDataTimeOff() : this.createForm();
    }

    getDataTimeOff() {
        this.sub = this.timeOffService.showAnTimeOff(this.pramRouter.id).subscribe(
            data => {
                this.showAnTimOff = data.time_off as ShowAnTimeOff;
                this.createForm();
            },
            error => {
                this.loadDataTimeOff = false;
                this.dataGlobalService.actionFail(error.error);
            }
        );
    }

    setReason(value: string): void {
        this.showAnTimOff.reason = value;
    }

    createForm(): void {
        this.loadDataTimeOff = false;
        if (this.pramRouter.type === 'di-muon-ve-som') {
            this.formTimeOffLate = this.fb.group({
                /* team_leader: [this.showAnTimOff.id === undefined ? '' : this.showAnTimOff.team_leader,
                     Validators.compose([Validators.required, Validators.email])],
                 project_manger: [this.showAnTimOff.id === undefined ? '' : this.showAnTimOff.project_manger,
                     Validators.compose([Validators.required, Validators.email])],*/
                backup_person: [this.showAnTimOff.id === undefined ? '' : this.showAnTimOff.backup_person,
                    Validators.compose([Validators.required, Validators.email])],
                day_of_absence: [this.showAnTimOff.id === undefined ? (moment().format('DD-MM-YYYY')) :
                    this.convertDate(this.showAnTimOff.start_datetime)],
                time_start_of_absence: [this.showAnTimOff.id === undefined ? '08:00' :
                    this.convertTime(this.showAnTimOff.start_datetime)],
                time_end_of_absence: [this.showAnTimOff.id === undefined ? '17:30' :
                    this.convertTime(this.showAnTimOff.end_datetime)],
                detailed_reason: [this.showAnTimOff.id === undefined ? '' : this.showAnTimOff.detailed_reason,
                    Validators.compose([Validators.required])],
                status: [this.showAnTimOff.id === undefined ? 1 : this.showAnTimOff.status],
                flow_type: [this.showAnTimOff.id === undefined ? 0 : this.showAnTimOff.flow_type],
            });
        }
        if (this.pramRouter.type === 'nghi-phep') {
            this.formTimeOffLate2 = this.fb.group({
                /* team_leader2: [this.showAnTimOff.id === undefined ? '' : this.showAnTimOff.team_leader,
                     Validators.compose([Validators.required, Validators.email])],
                 project_manger2: [this.showAnTimOff.id === undefined ? '' : this.showAnTimOff.project_manger,
                     Validators.compose([Validators.required, Validators.email])],*/
                backup_person2: [this.showAnTimOff.id === undefined ? '' : this.showAnTimOff.backup_person,
                    Validators.compose([Validators.required, Validators.email])],
                start_time_of_absence2: [this.showAnTimOff.id === undefined ? (moment().format('DD-MM-YYYY HH:mm')) :
                    this.convertDateTime(this.showAnTimOff.start_datetime)],
                end_time_of_absence2: [this.showAnTimOff.id === undefined ? (moment().format('DD-MM-YYYY HH:mm')) :
                    this.convertDateTime(this.showAnTimOff.end_datetime)],
                detailed_reason2: [this.showAnTimOff.id === undefined ? '' : this.showAnTimOff.detailed_reason,
                    Validators.compose([Validators.required])],
                status2: [this.showAnTimOff.id === undefined ? 5 : this.showAnTimOff.status],
                flow_type2: [this.showAnTimOff.id === undefined ? 0 : this.showAnTimOff.flow_type],
                forget_type: [this.showAnTimOff.id === undefined ? 0 : this.showAnTimOff.forget_type],
            });
        }
        if (this.pramRouter.id !== '') {
            /* this.checkEmail(1, this.showAnTimOff.project_manger);
             this.checkEmail(2, this.showAnTimOff.team_leader);*/
            if (this.showAnTimOff.status !== 4) {
                this.checkEmail(3, this.showAnTimOff.backup_person);
            }
        }
    }

    convertDate(time: string): string {
        const date = time.split(' ')[0];
        // console.log(date.split('-')[2] + '-' + date.split('-')[1] + '-' + date.split('-')[0])
        return date.split('-')[2] + '-' + date.split('-')[1] + '-' + date.split('-')[0];
    }

    convertDateTime(time: string): string {
        const ddmmyyyy = time.split(' ')[0];
        const hhmm = time.split(' ')[1];
        const date = ddmmyyyy.split('-')[2] + '-' + ddmmyyyy.split('-')[1] + '-' + ddmmyyyy.split('-')[0]
        // console.log(date + ' ' + hhmm)
        return date + ' ' + hhmm;
    }

    convertTime(time: string): string {
        // console.log(time.split(' ')[1]);
        return time.split(' ')[1];
    }

    submit(): void {
        this.pramRouter.type === 'di-muon-ve-som' ? this.createTimeOffLate() : this.createDayOff();
    }

    createTimeOffLate() {
        const daySelected = $('#day-of-absence').val() + '';
        const startTimeSelected = $('#time-start-of-absence').val() + '';
        const endTimeSelected = $('#time-end-of-absence').val() + '';
        this.showAnTimOff.employee_id = this.dataGlobalService.getEmployId();
        this.showAnTimOff.start_datetime = daySelected + ' ' + startTimeSelected;
        this.showAnTimOff.end_datetime = daySelected + ' ' + endTimeSelected;
        this.showAnTimOff.status = this.formTimeOffLate.get('status').value;
        this.showAnTimOff.flow_type = this.formTimeOffLate.get('flow_type').value;
        this.showAnTimOff.forget_type = null;
        this.showAnTimOff.approved = 0;
        this.showAnTimOff.approved_reason = '';
        this.showAnTimOff.reason = this.showAnTimOff.reason === undefined ? 'Lý do khác' : this.showAnTimOff.reason;
        this.showAnTimOff.detailed_reason = this.formTimeOffLate.get('detailed_reason').value;
        /*  this.showAnTimOff.project_manger = this.formTimeOffLate.get('project_manger').value;
          this.showAnTimOff.team_leader = this.formTimeOffLate.get('team_leader').value;*/
        this.showAnTimOff.backup_person = this.formTimeOffLate.get('backup_person').value;
        if (daySelected !== '' && startTimeSelected !== '' && endTimeSelected !== '') {
            this.showDialogConfirmSaveTimeOff();
        } else {
            this.errorMsg = 'Vui lòng nhập thời gian!';
        }
    }


    createDayOff() {
        const startTimeSelected245 = $('#start-time-of-absence245').val() + '';
        const startTimeSelected26 = $('#start-time-of-absence26').val() + '';
        const endTimeSelected2 = $('#end-time-of-absence2').val() + '';
        this.showAnTimOff.employee_id = this.showAnTimOff.id === undefined ?
            this.dataGlobalService.getEmployId() : this.showAnTimOff.employee_id;
        this.showAnTimOff.status = this.formTimeOffLate2.get('status2').value;
        this.showAnTimOff.flow_type = this.formTimeOffLate2.get('flow_type2').value;
        this.showAnTimOff.forget_type = (this.showAnTimOff.status === 4) ? this.formTimeOffLate2.get('forget_type').value : null;
        this.showAnTimOff.approved = 0;
        this.showAnTimOff.approved_reason = '';
        this.showAnTimOff.detailed_reason = this.formTimeOffLate2.get('detailed_reason2').value;
        /*   this.showAnTimOff.project_manger = this.formTimeOffLate2.get('project_manger2').value;
           this.showAnTimOff.team_leader = this.formTimeOffLate2.get('team_leader2').value;*/
        this.showAnTimOff.backup_person = this.formTimeOffLate2.get('backup_person2').value;
        this.showAnTimOff.reason = (this.showAnTimOff.reason === undefined || this.showAnTimOff.status === 4) ? 'Lý do khác'
            : this.showAnTimOff.reason;
        if (this.showAnTimOff.status === 6) {
            this.showAnTimOff.start_datetime = startTimeSelected26;
            this.showAnTimOff.end_datetime = endTimeSelected2;
            if (startTimeSelected26 !== '' && endTimeSelected2 !== '') {
                this.showDialogConfirmSaveTimeOff();
            } else {
                this.errorMsg = 'Vui lòng nhập thời gian!';
            }
        } else {
            this.showAnTimOff.start_datetime = startTimeSelected245 + ' ' + this.dataGlobalService.getTimeConfig().start_morning;
            this.showAnTimOff.end_datetime = startTimeSelected245 + ' ' + this.dataGlobalService.getTimeConfig().end_afternoon;
            startTimeSelected245 !== '' ? this.showDialogConfirmSaveTimeOff() : this.errorMsg = 'Vui lòng nhập thời gian!';
        }

    }


    showDialogConfirmSaveTimeOff() {
        this.errorMsg = '';
        swal({
            title: this.showAnTimOff.id === undefined ? 'Gửi đơn đăng ký vắng mặt này?' : 'Cập nhật đơn đăng ký vắng mặt này?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(r => {
                        resolve();
                    }, 500);
                });
            },
        }).then(
            btnOK => {
                this.sendDatatosever(this.showAnTimOff);
            }, btnCancel => {
            }
        );

    }

    sendDatatosever(data: any): void {
        // console.log(data);
        this.dataGlobalService.disBtnSubmit();
        if (this.pramRouter.id === '') {
            this.sub1 = this.timeOffService.create(data).subscribe(
                repo => {
                    this.showDialogSuccess();
                },
                error => {
                    this.dataGlobalService.enableBtnSubmit();
                    this.dataGlobalService.actionFail(error.error);
                });
        } else {
            this.sub1 = this.timeOffService.update(data, this.pramRouter.id).subscribe(
                repo => {
                    this.showDialogSuccess();
                },
                error => {
                    this.dataGlobalService.enableBtnSubmit();
                    this.dataGlobalService.actionFail(error.error);
                });
        }
    }

    showDialogSuccess() {
        this.dataGlobalService.enableBtnSubmit();
        swal({
            title: 'Thành công',
            text: this.showAnTimOff.id === undefined ? 'Bạn đã gửi đơn đăng ký vắng mặt!' : ' Bạn đã cập nhật đơn đăng ký vắng mặt!',
            type: 'success',
            confirmButtonText: 'Thoát'
        }).then(isConfirm => {
                this.goToBeforeScreen();
            }, isCancel => {
            }
        );
    }

    goToBeforeScreen(): void {
        this.ngOnDestroy();
        window.history.back();
    }


    deleteTimeOff() {
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
                this.isDeleteTimeOff = true;
                this.timeOffService.delete(this.pramRouter.id).subscribe(
                    repo => {
                        this.isDeleteTimeOff = false;
                        swal({
                                title: 'Thành công',
                                text: 'Đơn vắng mặt này đã bị xóa!',
                                type: 'success',
                                allowOutsideClick: false,
                            }
                        ).then(r => {
                            window.history.back();
                        }, f => {
                            window.history.back();
                        });

                    },
                    error => {
                        this.isDeleteTimeOff = false;
                        this.dataGlobalService.actionFail(error.error);
                    });
            },
            cencel => {

            });
    }


    focusOutFunction(tag: number) {
        let email = '';
        if (this.pramRouter.type === 'di-muon-ve-som') {
            switch (tag) {
                /* case 1:
                     email = this.formTimeOffLate.get('project_manger').value;
                     break;
                 case 2:
                     email = this.formTimeOffLate.get('team_leader').value;
                     break;*/
                case 3:
                    email = this.formTimeOffLate.get('backup_person').value;
                    break;
            }
        } else {
            switch (tag) {
                /*  case 1:
                      email = this.formTimeOffLate2.get('project_manger2').value;
                      break;
                  case 2:
                      email = this.formTimeOffLate2.get('team_leader2').value;
                      break;*/
                case 3:
                    email = this.formTimeOffLate2.get('backup_person2').value;
                    break;
            }
        }

        this.checkEmail(tag, email);
    }

    focusFuntion(tag: number) {
        switch (tag) {
            // case 1:
            //     this.focusOut1 = false;
            //     break;
            // case 2:
            //     this.focusOut2 = false;
            //     break;
            case 3:
                this.focusOut3 = false;
                break;
        }

    }

    focusOutFunctionblur(tag: number, event: any) {
        if (event.keyCode === 13) {
            switch (tag) {
                /*   case 1:
                       this.pramRouter.type === 'di-muon-ve-som' ? $('#pemail').blur() : $('#pemail2').blur();
                       break;
                   case 2:
                       this.pramRouter.type === 'di-muon-ve-som' ? $('#lemail').blur() : $('#lemail2').blur();
                       break;*/
                case 3:
                    this.pramRouter.type === 'di-muon-ve-som' ? $('#bemail').blur() : $('#bemail2').blur();
                    break;
            }
        }


    }

    private checkEmail(tag: number, email: string) {
        // console.log(email);
        this.dataGlobalService.checkEmailFocus(email).subscribe(
            data => {
                if (this.pramRouter.type === 'di-muon-ve-som') {
                    switch (tag) {
                        /*  case 1:
                              this.focusOut1 = true;
                              this.name1 = data.user.name;
                              this.formTimeOffLate.patchValue({
                                  project_manger: data.user.email,
                              });
                              break;
                          case 2:
                              this.focusOut2 = true;
                              this.name2 = data.user.name;
                              this.formTimeOffLate.patchValue({
                                  team_leader: data.user.email,
                              });
                              break;*/
                        case 3:
                            this.focusOut3 = true;
                            this.name3 = data.user.name;
                            this.formTimeOffLate.patchValue({
                                backup_person: data.user.email,
                            });
                            break;
                    }
                } else {
                    switch (tag) {
                        /*  case 1:
                              this.focusOut1 = true;
                              this.name1 = data.user.name;
                              this.formTimeOffLate2.patchValue({
                                  project_manger2: data.user.email,
                              });
                              break;
                          case 2:
                              this.focusOut2 = true;
                              this.name2 = data.user.name;
                              this.formTimeOffLate2.patchValue({
                                  team_leader2: data.user.email,
                              });
                              break;*/
                        case 3:
                            this.focusOut3 = true;
                            this.name3 = data.user.name;
                            this.formTimeOffLate2.patchValue({
                                backup_person2: data.user.email,
                            });
                            break;
                    }
                }


            }, error => {
                switch (tag) {
                    /* case 1:
                         this.focusOut1 = true;
                         this.name1 = '';
                         break;
                     case 2:
                         this.focusOut2 = true;
                         this.name2 = '';
                         break;*/
                    case 3:
                        this.focusOut3 = true;
                        this.name3 = '';
                        break;
                }
            }
        );
    }
}
