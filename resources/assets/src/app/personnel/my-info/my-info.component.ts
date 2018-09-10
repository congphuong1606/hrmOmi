import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, FormControl } from '@angular/forms';
import { log } from 'util';
import { HttpClient } from '@angular/common/http';
import { AuthService } from '../../services/authSevice';
import { Router, NavigationEnd, ActivatedRoute } from '@angular/router';
import { PersonnelService } from '../../services/personnel.service';
import { Employee } from '../../models/api/response/ListEmployeesResponse';
import { DataGlobalService } from '../../services/data.global.service';
import { LoaderController } from '../../shared/loader/loader';


@Component({
    selector: 'my-info-cmp',
    moduleId: module.id,
    templateUrl: 'my-info.component.html'
})

export class MyInfoComponent implements OnInit {
    form: FormGroup

    employee: Employee;
    id: any;

    loaderController: LoaderController = new LoaderController();

    isAdvancedAdmin = false;
    token;

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private router: Router,
        private route: ActivatedRoute,
        private personnelService: PersonnelService,
        private dataGlobalService: DataGlobalService,
        private authService: AuthService) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });

        this.id = this.dataGlobalService.getEmployId();
        this.employee = new Employee();
        this.isAdvancedAdmin = this.dataGlobalService.isAdvancedAdmin();
        this.loaderController.enableLoader();
        this.token = this.authService.getToken();
    }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-sach-nhan-su/ho-so')) {
            this.router.navigate(['/trang-chu']);
        } else {
            if (!this.isAdvancedAdmin) {
                this.loaderController.pushLoader();
                this.personnelService.getEmployee(this.id).subscribe(
                    data => {
                        this.loaderController.releaseLoader();
                        this.employee = data.employee;
                        console.log(this.employee)
                    },
                    error => {
                        this.loaderController.releaseLoader();
                        console.log(error);
                    }
                );
            }   
        }
    }

    goBack() {
        window.history.back();
    }
}