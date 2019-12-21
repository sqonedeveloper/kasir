import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Container, Row, Col, Breadcrumb, Form, Button } from 'react-bootstrap'
import axios from 'axios'

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
         tanggal: '',
         id_produk: '',
         kode: '',
         nama: '',
         detail: '',
         id_supplier: '',
         detail_lainnya: '',
         stok: ''
      }
   
      this._onChange = this._onChange.bind(this)
   }
   
   _onChange(e) {
      this.setState({ [e.target.name]: e.target.value })
   }

   _submit() {
      this.setState({ btnLoading: true })
      var formData = new FormData()
      
      axios.
         post('', formData).
         then(res => {
            var response = res.data
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
                  <h4 className="text-themecolor m-b-0 m-t-0">Stok</h4>
                  <Breadcrumb>
                     <Breadcrumb.Item active>Home</Breadcrumb.Item>
                     <Breadcrumb.Item active>Stok</Breadcrumb.Item>
                     <Breadcrumb.Item active>Masuk</Breadcrumb.Item>
                     <Breadcrumb.Item active>{document.getElementsByTagName('title')[0].innerText}</Breadcrumb.Item>
                  </Breadcrumb>
               </Col>
            </Row>
            <Row>
               <Col md={12}>
                  <div className="card">
                     <div className="card-body">
                        <Form.Group as={Row}>
                           <Form.Label column md={3}>Tanggal</Form.Label>
                           <Col md={9}>
                              <Form.Control size="sm" autoFocus type="date" />
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row}>
                           <Form.Label column md={3}>Barcode/Kode</Form.Label>
                           <Col md={9}>
                              <Form.Control size="sm" />
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row}>
                           <Form.Label column md={3}>Nama Produk</Form.Label>
                           <Col md={9}>
                              <Form.Control size="sm" />
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row}>
                           <Form.Label column md={3}>Detail</Form.Label>
                           <Col md={9}>
                              <Form.Control size="sm" />
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row}>
                           <Form.Label column md={3}>Supplier</Form.Label>
                           <Col md={9}>
                              <Form.Control size="sm" />
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row}>
                           <Form.Label column md={3}>Detail Lainnya</Form.Label>
                           <Col md={9}>
                              <Form.Control size="sm" />
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row}>
                           <Form.Label column md={3}>Jumlah Stok</Form.Label>
                           <Col md={9}>
                              <Form.Control size="sm" />
                           </Col>
                        </Form.Group>
                        <Col md={{ offset: 3, span: 9 }}>
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