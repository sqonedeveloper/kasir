import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Container, Row, Col, Breadcrumb, Button, Table, Form } from 'react-bootstrap'
import MsgResponse from '../../MsgResponse'
import axios from 'axios'

axios.defaults.baseURL = siteURL
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

class Satuan extends Component {
   constructor() {
      super()

      this.state = {
         btnLoading: false,
         errors: {},
         status: false,
         msg_response: '',
         nama: ''
      }

      this._onChange = this._onChange.bind(this)
   }

   componentDidMount() {
      if (pageType === 'update') {
         this.setState({ ...content.detail })
      }

      this.loadData = $('#datatable').DataTable({
         responsive: true,
         ordering: true,
         processing: true,
         serverSide: true,
         ajax: {
            url: siteURL + '/admin/produk/satuan/getData',
            type: 'POST'
         },
         createdRow: (row) => {
            var rows = row.cells[0].children[1].children
            var _edit = rows[0].children[0]
            var _delete = rows[1].children[0]
      
            _edit.onclick = () => {
               open(siteURL + '/admin/produk/satuan/edit/' + _edit.dataset.id, '_parent')
            }
      
            _delete.onclick = () => {
               if (confirm('Apakah anda yakin ingin menghapus?')) {
                  this._delete(_delete.dataset.id)
               }
            }
         },
         columns: [
            null
         ]
      });
   }

   _onChange(e) {
      this.setState({ [e.target.name]: e.target.value })
   }

   _delete(id) {
      this.setState({
         status: true,
         msg_response: 'Loading...'
      })

      var formData = new FormData()
      formData.append('pageType', 'delete')
      formData.append('id', id)
      
      axios.
         post('/admin/produk/satuan/delete', formData).
         then(res => {
            var response = res.data
            this.setState({ ...response })
            
            if (response.status) {
               this.loadData.ajax.reload(null, false)
            }
         }).
         catch(error => {
            console.log('Error', error.message)
         })
   }

   _submit() {
      this.setState({ btnLoading: true })
      var formData = new FormData()
      formData.append('pageType', pageType)
      formData.append('id', segment[5])
      formData.append('nama', this.state.nama)

      axios.
         post('/admin/produk/satuan/submit', formData).
         then(res => {
            var response = res.data
            this.setState({ ...response })

            if (response.status) {
               this.loadData.ajax.reload(null, false)

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
               <Col md={4}>
                  <div className="card">
                     <div className="card-body">
                        <Form.Group className={this.state.errors.nama ? 'has-danger' : ''}>
                           <Form.Label>Nama Satuan</Form.Label>
                           <Form.Control name="nama" value={this.state.nama} onChange={this._onChange} size="sm" />
                           <Form.Control.Feedback type="invalid">{this.state.errors.nama}</Form.Control.Feedback>
                        </Form.Group>
                        <Button
                           variant="success"
                           className="waves-effect waves-light"
                           size="sm"
                           onClick={this.state.btnLoading ? null : this._submit.bind(this)}
                        >{this.state.btnLoading ? 'Loading...' : 'Submit'}</Button>
                     </div>
                  </div>
               </Col>
               <Col md={8}>
                  <MsgResponse {...this.state} />
                  <div className="card">
                     <Table striped bordered hover size="sm" id="datatable">
                        <thead>
                           <tr>
                              <th>Nama Satuan</th>
                           </tr>
                        </thead>
                     </Table>
                  </div>
               </Col>
            </Row>
         </Container>
      )
   }
}

ReactDOM.render(<Satuan />, document.getElementById('root'))