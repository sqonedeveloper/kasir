import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Container, Row, Col, Breadcrumb, Form, Button } from 'react-bootstrap'
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
         email: '',
         telp: '',
         alamat: '',
         keterangan: ''
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
      formData.append('email', this.state.email)
      formData.append('telp', this.state.telp)
      formData.append('alamat', this.state.alamat)
      formData.append('keterangan', this.state.keterangan)
      
      axios.
         post('/admin/supplier/submit', formData).
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
                  <h4 className="text-themecolor m-b-0 m-t-0">Supplier</h4>
                  <Breadcrumb>
                     <Breadcrumb.Item active>Home</Breadcrumb.Item>
                     <Breadcrumb.Item active>Supplier</Breadcrumb.Item>
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
                           <Form.Label column md={2}>Nama</Form.Label>
                           <Col md={10}>
                              <Form.Control name="nama" value={this.state.nama} onChange={this._onChange} size="sm" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.nama}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.email ? 'has-danger' : ''}>
                           <Form.Label column md={2}>Email</Form.Label>
                           <Col md={10}>
                              <Form.Control name="email" value={this.state.email} onChange={this._onChange} size="sm" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.email}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.telp ? 'has-danger' : ''}>
                           <Form.Label column md={2}>Telepon</Form.Label>
                           <Col md={10}>
                              <Form.Control name="telp" value={this.state.telp} onChange={this._onChange} size="sm" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.telp}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.alamat ? 'has-danger' : ''}>
                           <Form.Label column md={2}>Alamat</Form.Label>
                           <Col md={10}>
                              <Form.Control name="alamat" value={this.state.alamat} onChange={this._onChange} size="sm" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.alamat}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row}>
                           <Form.Label column md={2}>Keterangan</Form.Label>
                           <Col md={10}>
                              <Form.Control name="keterangan" value={this.state.keterangan} onChange={this._onChange} size="sm" />
                           </Col>
                        </Form.Group>
                        <Col md={{ offset: 2, md: 10 }}>
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