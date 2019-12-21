import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Container, Row, Col, Breadcrumb, Form, Button } from 'react-bootstrap'
import MsgResponse from '../../../MsgResponse'
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
         kode: '',
         nama: '',
         harga: '',
         id_kategori: '',
         id_satuan: ''
      }

      this._onChange = this._onChange.bind(this)
   }

   _onChange(e) {
      this.setState({ [e.target.name]: e.target.value })

      if (e.target.name === 'harga') {
         var value = e.target.value
         this.setState({
            harga: value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')
         })
      }
   }

   _submit() {
      this.setState({ btnLoading: true })
      var formData = new FormData()
      formData.append('pageType', pageType)
      formData.append('id', segment[5])
      formData.append('kode', this.state.kode)
      formData.append('nama', this.state.nama)
      formData.append('harga', this.state.harga)
      formData.append('id_kategori', this.state.id_kategori)
      formData.append('id_satuan', this.state.id_satuan)
      
      axios.
         post('/admin/produk/dataProduk/submit', formData).
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
                  <h4 className="text-themecolor m-b-0 m-t-0">Produk</h4>
                  <Breadcrumb>
                     <Breadcrumb.Item active>Home</Breadcrumb.Item>
                     <Breadcrumb.Item active>Produk</Breadcrumb.Item>
                     <Breadcrumb.Item active>{document.getElementsByTagName('title')[0].innerText}</Breadcrumb.Item>
                  </Breadcrumb>
               </Col>
            </Row>
            <Row>
               <Col md={12}>
                  <div className="card">
                     <div className="card-body">
                        <Col md={12}>
                           <Row>
                              <Col md={6}>
                                 <Form.Group as={Row} className={this.state.errors.kode ? 'has-danger' : ''}>
                                    <Form.Label column md={3}>Barcode/Kode</Form.Label>
                                    <Col md={9}>
                                       <Form.Control name="kode" value={this.state.kode} onChange={this._onChange} size="sm" autoFocus />
                                       <Form.Control.Feedback type="invalid">{this.state.errors.kode}</Form.Control.Feedback>
                                    </Col>
                                 </Form.Group>
                                 <Form.Group as={Row} className={this.state.errors.nama ? 'has-danger' : ''}>
                                    <Form.Label column md={3}>Nama</Form.Label>
                                    <Col md={9}>
                                       <Form.Control name="nama" value={this.state.nama} onChange={this._onChange} size="sm" />
                                       <Form.Control.Feedback type="invalid">{this.state.errors.nama}</Form.Control.Feedback>
                                    </Col>
                                 </Form.Group>
                                 <Form.Group as={Row} className={this.state.errors.harga ? 'has-danger' : ''}>
                                    <Form.Label column md={3}>Harga</Form.Label>
                                    <Col md={9}>
                                       <Form.Control name="harga" value={this.state.harga} onChange={this._onChange} size="sm" />
                                       <Form.Control.Feedback type="invalid">{this.state.errors.harga}</Form.Control.Feedback>
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
                              </Col>
                              <Col md={6}>
                                 <Form.Group as={Row} className={this.state.errors.id_kategori ? 'has-danger' : ''}>
                                    <Form.Label column md={3}>Kategori</Form.Label>
                                    <Col md={9}>
                                       <Form.Control name="id_kategori" value={this.state.id_kategori} onChange={this._onChange} size="sm" as="select">
                                          <option value="">--pilih--</option>
                                          {content.listsKategori.map((data, key) => {
                                             return <option value={data.value} key={key}>{data.label}</option>
                                          })}
                                       </Form.Control>
                                       <Form.Control.Feedback type="invalid">{this.state.errors.id_kategori}</Form.Control.Feedback>
                                    </Col>
                                 </Form.Group>
                                 <Form.Group as={Row} className={this.state.errors.id_satuan ? 'has-danger' : ''}>
                                    <Form.Label column md={3}>Satuan</Form.Label>
                                    <Col md={9}>
                                       <Form.Control name="id_satuan" value={this.state.id_satuan} onChange={this._onChange} size="sm" as="select">
                                          <option value="">--pilih--</option>
                                          {content.listsSatuan.map((data, key) => {
                                             return <option value={data.value} key={key}>{data.label}</option>
                                          })}
                                       </Form.Control>
                                       <Form.Control.Feedback type="invalid">{this.state.errors.id_satuan}</Form.Control.Feedback>
                                    </Col>
                                 </Form.Group>
                              </Col>
                           </Row>
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