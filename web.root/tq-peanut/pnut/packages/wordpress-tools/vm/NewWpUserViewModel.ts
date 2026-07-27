/// <reference path="../../../../pnut/core/ViewModelBase.ts" />
/// <reference path='../../../../typings/knockout/knockout.d.ts' />
/// <reference path='../../../../pnut/core/Peanut.d.ts' />

namespace WordpressTools {

    interface INewUserRequest {
        username: string;
        email: string;
        firstName: string;
        lastName: string;
        password? : string;
    }

    interface INewUserResponse {
        newUser: string;
        resetKey: string;
        url: string;
    }
    export class NewWpUserViewModel extends Peanut.ViewModelBase {
        // observables
        inputForm = {
            username: ko.observable<string>(''),
            email: ko.observable<string>(''),
            password: ko.observable<string>(''),
            firstName: ko.observable<string>(''),
            lastName: ko.observable<string>(''),
            nameError: ko.observable<boolean>(''),
            emailError: ko.observable<boolean>(''),
            usernameError: ko.observable<boolean>('')
        }

        newuserResponse = {
            success: ko.observable<boolean>(),
            newUser: ko.observable<string>(),
            resetKey: ko.observable<string>(),
            url: ko.observable<string>()
        }

        busy = ko.observable<boolean>(false);


        init(successFunction?: () => void) {
            let me = this;
            Peanut.logger.write('NewWpUserViewModel Init');
            me.application.loadResources([
                '@pnut/ViewModelHelpers.js'
            ], () => {
                me.bindDefaultSection();
                successFunction();
            });
        }

        clearInputForm = () => {
            this.inputForm.username('');
            this.inputForm.email('');
            this.inputForm.password('');
            this.inputForm.firstName('');
            this.inputForm.lastName('');
            this.inputForm.nameError(false);
            this.inputForm.emailError(false);
        }

        validateForm = (): INewUserRequest => {
            let me = this;
            let newUserRequest: INewUserRequest = {
                username: me.inputForm.username().trim(),
                email: me.inputForm.email().trim(),
                firstName: me.inputForm.firstName().trim(),
                lastName: me.inputForm.lastName().trim(),
                password: me.inputForm.password().trim()
            };
            let valid = true;
            if (newUserRequest.username === '') {
                me.inputForm.usernameError(true);
                valid = false;
            }
            if (newUserRequest.email === '') {
                me.inputForm.emailError(true);
                valid = false;
            }
            if (newUserRequest.lastName === '' && newUserRequest.firstName === '') {
                me.inputForm.nameError(true);
                valid = false;
            }
            if (!Peanut.Helper.ValidateEmail(newUserRequest.email)) {
                me.inputForm.emailError(true);
                valid = false;
            }

            return valid ? newUserRequest : null;
        }

        makeUser = () => {
            let me = this;
            let request = me.validateForm();
            if (!request) {
                return;
            }

            me.busy(true);
            me.newuserResponse.success(false);
            me.application.hideServiceMessages();
            me.application.showWaiter('Creating user..');
            me.services.executeService('peanut.wordpress-tools::MakeWpUser', request,
                function (serviceResponse: Peanut.IServiceResponse) {
                    if (serviceResponse.Result == Peanut.serviceResultSuccess) {
                        let response = serviceResponse.Value;
                        me.newuserResponse.success(true);
                        me.newuserResponse.newUser(response.newUser);
                        me.newuserResponse.resetKey(response.resetKey);
                        me.newuserResponse.url(response.url);
                        me.clearInputForm();
                    }
                }
            ).fail(function () {
                let trace = me.services.getErrorInformation();
            }).always(function () {
                me.application.hideWaiter();
                me.busy(false);
            });
        }
    }
}