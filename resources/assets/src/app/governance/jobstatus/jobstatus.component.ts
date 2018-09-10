import { Component, OnInit, OnDestroy } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";
import { Router } from '@angular/router';
import { DataGlobalService } from "../../services/data.global.service";
import { CategoryService } from "../../services/category/category.service";
import { CategoryReponse } from "../../models/api/response/CategoriesReponse";
import { Subscription } from "rxjs/Subscription";
declare var $: any;
declare var swal: any;


@Component({
    selector: 'jobstatus-cmp',
    moduleId: module.id,
    templateUrl: 'jobstatus.component.html'
})

export class JobStatusComponent implements OnInit, OnDestroy {

    dataRows: CategoryReponse[] = [];

    check = false;
    form: FormGroup;
    subscription: Subscription;
    subscriptionRemove: Subscription;
    isAdmin = false;
    allowUpdate = false;
    allowAdd = false;

    setupData(): void {
        this.isAdmin = this.dataGlobalService.isAdmin();
        this.allowUpdate = this.checkPemision('/danh-muc/cap-nhat-trang-thai-cong-viec');
        this.allowAdd = this.checkPemision('/danh-muc/them-trang-thai-cong-viec');

    }


    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
        this.subscriptionRemove !== undefined ? this.subscriptionRemove.unsubscribe() : console.log(':D');
    }
    constructor(private router: Router,
        private serrvice: CategoryService,
        private dataGlobalService: DataGlobalService) { }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-muc/trang-thai-cong-viec')) {
            window.history.back();
        } else {
            this.setupData();
            this.getListJobStatus();
        }

    }
    checkPemision(path: string): boolean {
        return this.dataGlobalService.checkPemisson(path);
    }
    getListJobStatus(): void {
        this.subscription = this.serrvice.getList('job_status').subscribe(
            repo => {
                console.log(repo);
                this.check = true;
                this.dataRows = (repo.jobs_status as CategoryReponse[]);
            },
            error => {
                this.dataGlobalService.actionFail(error.error);
            });
    }
    updateJobStatus(data: any): void {
        this.router.navigate(['../danh-muc/cap-nhat-trang-thai-cong-viec'], { queryParams: data });
    }
    addJobStatusAction(): void {
        this.router.navigate(['../danh-muc/them-trang-thai-cong-viec']);
    }
    onBack(): void {
        this.ngOnDestroy();
        window.history.back();
    }

    deleteJobStatus(rl: any): void {
        swal({
            title: 'Xác nhận xóa?',
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
            this.subscriptionRemove = this.serrvice.delete('job_status', rl.id).subscribe(
                repo => {
                    if (repo.status === 'success') {
                        swal('Đã Xóa!', 'Bạn đã xóa trạng thái công việc thành công ', 'success');
                        this.dataRows = this.dataRows.filter(h => h !== rl);
                    }
                },
                error => {
                    this.dataGlobalService.actionFail(error.error);
                });
        });
    }

    isLoadSuccess(): boolean {
        if (this.check) {
            $('[rel="tooltip"]').tooltip();

        }

        return this.check;
    }



}