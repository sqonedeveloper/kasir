import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Container, Row, Col, Breadcrumb, Button, Form } from 'react-bootstrap'
import MsgResponse from '../MsgResponse'
import axios from 'axios'

axios.defaults.baseURL = siteURL
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

class Settings extends Component {
   constructor() {
      super()
   
      this.state = {
         btnLoading: false,
         errors: {},
         status: false,
         msg_response: '',
         nama_toko: '',
         telp: '',
         prefix_nota: '',
         prefix_kode: ''
      }
   
      this._onChange = this._onChange.bind(this)
   }

   componentDidMount() {
      this.setState({ ...content.detail })
   }
   
   _onChange(e) {
      this.setState({ [e.target.name]: e.target.value })
   }

   _submit() {
      this.setState({ btnLoading: true })
      var formData = new FormData()
      formData.append('nama_toko', this.state.nama_toko)
      formData.append('telp', this.state.telp)
      formData.append('prefix_nota', this.state.prefix_nota)
      formData.append('prefix_kode', this.state.prefix_kode)
      
      axios.
         post('/admin/settings/submit', formData).
         then(res => {
            var response = res.data
            this.setState({ ...response })
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
                  <h4 className="text-themecolor m-b-0 m-t-0">Settings</h4>
                  <Breadcrumb>
                     <Breadcrumb.Item active>Home</Breadcrumb.Item>
                     <Breadcrumb.Item active>{document.getElementsByTagName('title')[0].innerText}</Breadcrumb.Item>
                  </Breadcrumb>
               </Col>
            </Row>
            <Row>
               <Col md={12}>
                  <MsgResponse {...this.state} />
                  <div className="card">
                     <div className="card-body">
                        <Form.Group as={Row} className={this.state.errors.nama_toko ? 'has-danger' : ''}>
                           <Form.Label column md={2}>Nama Toko</Form.Label>
                           <Col md={10}>
                              <Form.Control name="nama_toko" value={this.state.nama_toko} onChange={this._onChange} size="sm" autoFocus />
                              <Form.Control.Feedback type="invalid">{this.state.errors.nama_toko}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.telp ? 'has-danger' : ''}>
                           <Form.Label column md={2}>Telepon/HP</Form.Label>
                           <Col md={10}>
                              <Form.Control name="telp" value={this.state.telp} onChange={this._onChange} size="sm" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.telp}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.prefix_nota ? 'has-danger' : ''}>
                           <Form.Label column md={2}>Prefix Nota</Form.Label>
                           <Col md={10}>
                              <Form.Control name="prefix_nota" value={this.state.prefix_nota} onChange={this._onChange} size="sm" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.prefix_nota}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.prefix_kode ? 'has-danger' : ''}>
                           <Form.Label column md={2}>Prefix Barcode/Kode</Form.Label>
                           <Col md={10}>
                              <Form.Control name="prefix_kode" value={this.state.prefix_kode} onChange={this._onChange} size="sm" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.prefix_kode}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Col md={{ span: 10, offset: 2 }}>
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

ReactDOM.render(<Settings />, document.getElementById('root'))