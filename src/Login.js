import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Form, Button } from 'react-bootstrap'
import axios from 'axios'
import MsgResponse from './MsgResponse'

axios.defaults.baseURL = siteURL
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

class Login extends Component {
   constructor() {
      super()
   
      this.state = {
         btnLoading: false,
         errors: {},
         status: false,
         msg_response: '',
         username: '',
         password: ''
      }
   
      this._onChange = this._onChange.bind(this)
   }
   
   _onChange(e) {
      this.setState({ [e.target.name]: e.target.value })
   }

   _submit() {
      this.setState({ btnLoading: true })
      var formData = new FormData()
      formData.append('username', this.state.username)
      formData.append('password', this.state.password)
      
      axios.
         post('/login/submit', formData).
         then(res => {
            var response = res.data
            this.setState({ ...response })

            if (response.status) {
               if (response.role === '1') {
                  open(siteURL + '/admin/dashboard', '_parent')
               } else if (response.role === '2') {
                  open(siteURL + '/kasir/transaksi', '_parent')
               }
            }
         }).
         catch(error => {
            console.log('Error', error.message)
         }).
         finally(() => {
            this.setState({ btnLoading: false })
         })
   }

   render() {
      return (
         <div className="login-box card">
            <div className="card-body">
               <Form className="form-horizontal form-material">
                  <div className="text-center d-block" style={{ fontSize: 30, fontWeight: 400 }}>Toko Appmurah</div>
                  <MsgResponse {...this.state} />
                  <Form.Group className="mt-4">
                     <Form.Control name="username" value={this.state.username} onChange={this._onChange} placeholder="Username" autoFocus />
                  </Form.Group>
                  <Form.Group>
                     <Form.Control name="password" value={this.state.password} onChange={this._onChange} placeholder="Password" type="password" />
                  </Form.Group>
                  <Form.Group className="text-center mt-3">
                     <Button
                        variant="info"
                        className="btn-block text-uppercase waves-effect waves-light"
                        size="lg"
                        onClick={this.state.btnLoading ? null : this._submit.bind(this)}
                     >{this.state.btnLoading ? 'Loading...' : 'Log In'}</Button>
                  </Form.Group>
               </Form>
            </div>
         </div>
      )
   }
}

ReactDOM.render(<Login />, document.getElementById('wrapper'))