import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Container, Row, Col, Breadcrumb, Button, Form } from 'react-bootstrap'
import axios from 'axios'
import MsgResponse from '../../MsgResponse'

axios.defaults.baseURL = siteURL
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

class Forms extends Component {
   constructor() {
      super()

      this.state = {
         btnLoading: false,
         errors: {},
         status: false,
         msg_response: '',
         nama: '',
         username: '',
         password: '',
         telp: '',
         role: ''
      }

      this._onChange = this._onChange.bind(this)
   }

   componentDidMount() {
      if (pageType === 'update') {
         this.setState({ ...content.detail })
      }
   }

   _onChange(e) {
      this.setState({ [e.target.name]: e.target.value })
   }

   _submit() {
      this.setState({ btnLoading: true })
      var formData = new FormData()
      formData.append('pageType', pageType)
      formData.append('id', segment[4])
      formData.append('nama', this.state.nama)
      formData.append('username', this.state.username)
      formData.append('password', this.state.password)
      formData.append('telp', this.state.telp)
      formData.append('role', this.state.role)
      
      axios.
         post('/admin/akun/submit', formData).
         then(res => {
            var response = res.data
            this.setState({ ...response })

            if (response.status) {
               if (pageType === 'insert') {
                  this.setState({ ...response.emptyPost })
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
         <Container fluid={true}>
            <Row className="page-titles">
               <Col md={12} className="align-self-center">
                  <h4 className="text-themecolor m-b-0 m-t-0">Akun</h4>
                  <Breadcrumb>
                     <Breadcrumb.Item active>Home</Breadcrumb.Item>
                     <Breadcrumb.Item active>Akun</Breadcrumb.Item>
                     <Breadcrumb.Item active>{document.getElementsByTagName('title')[0].innerText}</Breadcrumb.Item>
                  </Breadcrumb>
               </Col>
            </Row>
            <Row>
               <Col md={12}>
                  <MsgResponse {...this.state} />
                  <div className="card">
                     <div className="card-body">
                        <Form.Group as={Row} className={this.state.errors.nama ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Nama Lengkap</Form.Label>
                           <Col md={9}>
                              <Form.Control name="nama" value={this.state.nama} onChange={this._onChange} size="sm" autoFocus />
                              <Form.Control.Feedback type="invalid">{this.state.errors.nama}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.username ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Username</Form.Label>
                           <Col md={9}>
                              <Form.Control name="username" value={this.state.username} onChange={this._onChange} size="sm" disabled={pageType === 'update' ? true : false} />
                              <Form.Control.Feedback type="invalid">{this.state.errors.username}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.password ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Password</Form.Label>
                           <Col md={9}>
                              <Form.Control name="password" value={this.state.password} onChange={this._onChange} size="sm" type="password" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.password}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.telp ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Telepon/HP</Form.Label>
                           <Col md={9}>
                              <Form.Control name="telp" value={this.state.telp} onChange={this._onChange} size="sm" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.telp}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.role ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Roles</Form.Label>
                           <Col md={9}>
                              <Form.Control name="role" value={this.state.role} onChange={this._onChange} size="sm" as="select">
                                 <option value="">--pilih--</option>
                                 <option value="1">Administrator</option>
                                 <option value="2">Teller</option>
                              </Form.Control>
                              <Form.Control.Feedback type="invalid">{this.state.errors.role}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Col md={{ span: 9, offset: 3 }}>
                           <Button
                              variant="success"
                              className="waves-effect waves-light"
                              size="sm"
                              onClick={this.state.btnLoading ? null : this._submit.bind(this)}
                           >{this.state.btnLoading ? 'Loading...' : 'Submit'}</Button>
                        </Col>
                     </div>
                  </div>
               </Col>
            </Row>
         </Container>
      )
   }
}

ReactDOM.render(<Forms />, document.getElementById('root'))