# Routes (Phase 0 baseline)

Captured with `php artisan route:list --except-vendor`. 61 routes. The five licence API routes under `/wp-json/elevenrank-licence/v1/` are frozen.

| Method | URI | Handler | Name |
|---|---|---|---|
| GET | / | Illuminate\Routing\ViewController |  |
| GET | /account | Account\DashboardController@overview |  |
| GET | /account/download | Account\DashboardController@download |  |
| GET | /account/downloads | Account\DashboardController@downloads |  |
| GET | /account/licences | Account\DashboardController@licences |  |
| POST | /account/licences/{licence}/deactivate | Account\DashboardController@deactivateSite |  |
| GET | /account/login | Account\AuthController@login | account.login |
| POST | /account/login | Account\AuthController@authenticate |  |
| POST | /account/logout | Account\AuthController@logout |  |
| GET | /account/orders | Account\DashboardController@orders |  |
| GET | /account/orders/{order} | Account\DashboardController@order |  |
| GET | /account/profile | Account\DashboardController@profile |  |
| POST | /account/profile | Account\DashboardController@updateProfile |  |
| GET | /account/register | Account\AuthController@register |  |
| POST | /account/register | Account\AuthController@store |  |
| GET | /account/tickets | Account\DashboardController@tickets |  |
| POST | /account/tickets | Account\DashboardController@storeTicket |  |
| GET | /account/tickets/{ticket} | Account\DashboardController@ticket |  |
| POST | /account/tickets/{ticket}/reply | Account\DashboardController@replyTicket |  |
| GET | /admin | Admin\StaffController@overview |  |
| GET | /admin/audit | Admin\StaffController@audit |  |
| GET | /admin/customers | Admin\StaffController@customers |  |
| GET | /admin/customers/{user} | Admin\StaffController@customer |  |
| GET | /admin/licences | Admin\LicenceController@index |  |
| POST | /admin/licences | Admin\LicenceController@store |  |
| POST | /admin/licences/{licence} | Admin\LicenceController@update |  |
| POST | /admin/licences/{licence}/status | Admin\LicenceController@setStatus |  |
| GET | /admin/login | Admin\AuthController@show | login |
| POST | /admin/login | Admin\AuthController@login |  |
| POST | /admin/logout | Admin\AuthController@logout |  |
| GET | /admin/orders | Admin\StaffController@orders |  |
| POST | /admin/orders/{order} | Admin\StaffController@orderAction |  |
| GET | /admin/plans | Admin\StaffController@plans |  |
| POST | /admin/plans/{plan} | Admin\StaffController@updatePlan |  |
| GET | /admin/releases | Admin\ReleaseController@index |  |
| POST | /admin/releases | Admin\ReleaseController@store |  |
| POST | /admin/releases/restore | Admin\ReleaseController@restore |  |
| GET | /admin/system | Admin\StaffController@system |  |
| POST | /admin/system | Admin\StaffController@runSystem |  |
| GET | /admin/tickets | Admin\StaffController@tickets |  |
| GET | /admin/tickets/{ticket} | Admin\StaffController@ticket |  |
| POST | /admin/tickets/{ticket}/reply | Admin\StaffController@replyTicket |  |
| GET | /changelog | Illuminate\Routing\ViewController |  |
| GET | /checkout/{plan} | Account\DashboardController@checkout |  |
| POST | /checkout/{plan} | Account\DashboardController@placeOrder |  |
| GET | /docs | Illuminate\Routing\ViewController |  |
| GET | /docs/hooks | Illuminate\Routing\ViewController |  |
| GET | /features | Illuminate\Routing\ViewController |  |
| GET | /gpl | SiteController@gpl |  |
| GET | /pricing | SiteController@pricing |  |
| GET | /privacy | SiteController@privacy |  |
| GET | /pro | Illuminate\Routing\ViewController |  |
| GET | /refunds | SiteController@refunds |  |
| GET | /support | Illuminate\Routing\ViewController |  |
| GET | /terms | SiteController@terms |  |
| POST | /webhooks/paddle | PaddleWebhookController |  |
| POST | /wp-json/elevenrank-licence/v1/activate | LicenceApiController@activate |  |
| POST | /wp-json/elevenrank-licence/v1/check | LicenceApiController@check |  |
| POST | /wp-json/elevenrank-licence/v1/deactivate | LicenceApiController@deactivate |  |
| GET | /wp-json/elevenrank-licence/v1/download | LicenceApiController@download | licence.download |
| POST | /wp-json/elevenrank-licence/v1/update | LicenceApiController@update |  |
