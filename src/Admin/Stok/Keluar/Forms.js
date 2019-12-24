import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Container, Row, Col, Breadcrumb, Button, Form } from 'react-bootstrap'
import MsgResponse from '../../../MsgResponse'
import axios from 'axios'

axios.defaults.baseURL = siteURL
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

var Typeahead = require('react-bootstrap-typeahead').Typeahead

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
         stok: '',
         sisa_stok: 0,
         detail_lainnya: '',
         listsProduk: []
      }

      this._onChange = this._onChange.bind(this)
   }

   componentDidMount() {
      this.setState({ ...content })
   }

   _onChange(e) {
      this.setState({ [e.target.name]: e.target.value })
   }

   _submit() {
      this.setState({ btnLoading: true })
      var formData = new FormData()
      formData.append('pageType', pageType)
      formData.append('tanggal', this.state.tanggal)
      formData.append('id_produk', this.state.id_produk)
      formData.append('stok', this.state.stok)
      formData.append('sisa_stok', this.state.sisa_stok)
      formData.append('detail_lainnya', this.state.detail_lainnya)
      
      axios.
         post('/admin/stok/keluar/submit', formData).
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
                  <h4 className="text-themecolor m-b-0 m-t-0">Stok</h4>
                  <Breadcrumb>
                     <Breadcrumb.Item active>Home</Breadcrumb.Item>
                     <Breadcrumb.Item active>Stok</Breadcrumb.Item>
                     <Breadcrumb.Item active>Keluar</Breadcrumb.Item>
                     <Breadcrumb.Item active>{document.getElementsByTagName('title')[0].innerText}</Breadcrumb.Item>
                  </Breadcrumb>
               </Col>
            </Row>
            <Row>
               <Col md={12}>
                  <MsgResponse {...this.state} />
                  <div className="card">
                     <div className="card-body">
                        <Form.Group as={Row} className={this.state.errors.tanggal ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Tanggal</Form.Label>
                           <Col md={9}>
                              <Form.Control name="tanggal" value={this.state.tanggal} onChange={this._onChange} size="sm" autoFocus type="date" />
                              <Form.Control.Feedback type="invalid">{this.state.errors.tanggal}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.id_produk ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Barcode/Kode</Form.Label>
                           <Col md={9}>
                              <Typeahead
                                 bsSize="sm"
                                 placeholder="Ketikkan Barcode/kode disini..."
                                 options={this.state.listsProduk}
                                 onChange={e => {
                                    e.length > 0 ? this.setState({
                                       id_produk: e[0].value,
                                       sisa_stok: (e[0].total_stok_masuk - e[0].total_stok_keluar)
                                    }) : this.setState({ id_produk: '', sisa_stok: 0 })
                                 }}
                              />
                              <Form.Control.Feedback type="invalid">{this.state.errors.id_produk}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.sisa_stok ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Sisa Stok Sekarang</Form.Label>
                           <Col md={9}>
                              <Form.Control value={this.state.sisa_stok} size="sm" disabled={true} />
                              <Form.Control.Feedback type="invalid">{this.state.errors.sisa_stok}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row} className={this.state.errors.stok ? 'has-danger' : ''}>
                           <Form.Label column md={3}>Stok</Form.Label>
                           <Col md={9}>
                              <Form.Control name="stok" value={this.state.stok} onChange={this._onChange} size="sm" placeholder="Ketikkan jumlah stok yang keluar..." />
                              <Form.Control.Feedback type="invalid">{this.state.errors.stok}</Form.Control.Feedback>
                           </Col>
                        </Form.Group>
                        <Form.Group as={Row}>
                           <Form.Label column md={3}>Keterangan</Form.Label>
                           <Col md={9}>
                              <Form.Control name="detail_lainnya" value={this.state.detail_lainnya} onChange={this._onChange} size="sm" />
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