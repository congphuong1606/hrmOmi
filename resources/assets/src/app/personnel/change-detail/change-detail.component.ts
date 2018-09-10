import { Component, OnInit, OnDestroy } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";
import { PersonnelService } from "../../services/personnel.service";
import { Router, ActivatedRoute } from "@angular/router";
import { EmployeeChange } from "../../models/api/response/ListEmployeesResponse";
import { Subscription } from "rxjs/Subscription";
import { LoaderController } from "../../shared/loader/loader";

declare var $: any;
declare var swal: any;


@Component({
    selector: 'change-detail-cmp',
    moduleId: module.id,
    templateUrl: 'change-detail.component.html'
})

export class ChangelDetailComponent implements OnInit, OnDestroy {

    form: FormGroup;
    id;
    change: EmployeeChange;
    listServices: Subscription[] = [];
    loaderController: LoaderController = new LoaderController();

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private personnelService: PersonnelService,
        private router: Router,
        private route: ActivatedRoute,
        private authService: AuthService) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });
        this.change = new EmployeeChange();
    }

    ngOnInit() {
        this.id = this.route.snapshot.paramMap.get('id');
        this.loaderController.enableLoader();
        this.loaderController.pushLoader();
        this.personnelService.getEmployeeChange(this.id).subscribe(
            data => {
                this.loaderController.releaseLoader();
                this.change = data.employee;
            },
            error => {
                this.loaderController.releaseLoader();
            }
        );
    }

    ngOnDestroy(): void {
        this.listServices.forEach((value) => {
            value.unsubscribe();
        });
    }

    approve() {
        this.loaderController.pushLoader();
        this.personnelService.approveChange(this.id).subscribe(
            data => {
                this.loaderController.releaseLoader();
                swal({
                    title: 'Thành công',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result) {
                        this.router.navigate(['./danh-sach-nhan-su/quan-ly-thay-doi']);
                    }
                }).catch(swal.noop);
            },
            error => {
                this.loaderController.releaseLoader();
                swal({
                    title: 'Đã có lỗi xảy ra',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                });
            }
        );
    }

    reject() {
        this.loaderController.pushLoader();
        this.personnelService.rejectChange(this.id).subscribe(
            data => {
                this.loaderController.releaseLoader();
                swal({
                    title: 'Thành công',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result) {
                        this.router.navigate(['./danh-sach-nhan-su/quan-ly-thay-doi']);
                    }
                }).catch(swal.noop);
            },
            error => {
                this.loaderController.releaseLoader();
                swal({
                    title: 'Đã có lỗi xảy ra',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                });
            }
        );
    }
}