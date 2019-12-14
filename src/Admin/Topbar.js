import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { LazyLoadImage } from 'react-lazy-load-image-component'

class Topbar extends Component {
   render() {
      return (
         <header className="topbar">
            <nav className="navbar top-navbar navbar-expand-md navbar-light">
               <div className="navbar-header">
                  <a className="navbar-brand" href={siteURL}>
                     <span>
                        <LazyLoadImage src="https://wrappixel.com/demos/admin-templates/material-pro/assets/images/logo-text.png" effect="blur" className="dark-logo" />
                        <LazyLoadImage src="https://wrappixel.com/demos/admin-templates/material-pro/assets/images/logo-light-text.png" effect="blur" className="light-logo" />
                     </span>
                  </a>
               </div>
               <div className="navbar-collapse">
                  <ul className="navbar-nav mr-auto mt-md-0">
                     <li className="nav-item"> <a className="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="#"><i className="mdi mdi-menu" /></a></li>
                     <li className="nav-item"> <a className="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="#"><i className="ti-menu" /></a></li>
                  </ul>
                  <ul className="navbar-nav my-lg-0">
                     <li className="nav-item dropdown">
                        <a className="nav-link dropdown-toggle text-muted waves-effect waves-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <LazyLoadImage src={baseURL + 'assets/img/avatar.png'} effect="blur" className="profile-pic" />
                        </a>
                        <div className="dropdown-menu dropdown-menu-right scale-up">
                           <ul className="dropdown-user">
                              <li>
                                 <div className="dw-user-box">
                                    <div className="u-img">
                                       <LazyLoadImage src={baseURL + 'assets/img/avatar.png'} effect="blur" />
                                    </div>
                                    <div className="u-text">
                                       <h4>Fullname</h4>
                                       <p className="text-muted">Email</p>
                                    </div>
                                 </div>
                              </li>
                              <li role="separator" className="divider"></li>
                              <li><a href={siteURL + '/admin/users/profile'}><i className="ti-user" /> My Profile</a></li>
                              <li><a href={siteURL + '/login/logout'}><i className="fa fa-power-off" /> Logout</a></li>
                           </ul>
                        </div>
                     </li>
                  </ul>
               </div>
            </nav>
         </header>
      )
   }
}

ReactDOM.render(<Topbar />, document.getElementById('header'))