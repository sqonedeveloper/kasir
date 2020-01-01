import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { rupiah } from '../../Helpers'

var settings = content.settings
var detail = content.detail
var harga_jual = 0
var diskon = 0

class CetakStruk extends Component {
   componentDidMount() {
      window.print()
      window.close()
   }

   render() {
      return (
         <>
            <div className="title">
               {settings.nama_toko} <br />
               {settings.alamat} <br />
               {settings.telp}
            </div>
            <div className="head-desc">
               <div className="date">{detail.nota}</div>
               <div className="user">{detail.tgl_transaksi}</div>
            </div>
            <div className="head-desc">
               <div className="date" />
               <div className="user">{detail.users}</div>
            </div>
            <div className="separate" />
            <div className="transaction">
               <table className="transaction-table" cellspacing="0" cellpadding="0">
                  {detail.lists.map((data, key) => {
                     harga_jual += data.harga * data.jumlah
                     diskon += data.diskon
                     return (
                        <tr key={key}>
                           <td className="name">{data.produk}</td>
                           <td className="qty">{data.jumlah}</td>
                           <td className="sell-price">{rupiah(data.harga)}</td>
                           <td className="final-price">{rupiah(data.harga * data.jumlah)}</td>
                        </tr>
                     )
                  })}
                  <tr className="price-tr">
                     <td colspan="4">
                        <div className="separate-line" />
                     </td>
                  </tr>
                  <tr>
                     <td colspan="3" className="final-price">Harga Jual</td>
                     <td className="final-price">{rupiah(harga_jual)}</td>
                  </tr>
                  <tr className="discount-tr">
                     <td colspan="4">
                        <div className="separate-line" />
                     </td>
                  </tr>
                  <tr>
                     <td colspan="3" className="final-price">DISKON</td>
                     <td className="final-price">{rupiah(diskon + detail.diskon_pembayaran)}</td>
                  </tr>
                  <tr>
                     <td colspan="3" className="final-price">TOTAL</td>
                     <td className="final-price">{rupiah(harga_jual - (diskon + detail.diskon_pembayaran))}</td>
                  </tr>
                  <tr>
                     <td colspan="3" className="final-price">Bayar</td>
                     <td className="final-price">{rupiah(detail.jumlah_bayar)}</td>
                  </tr>
                  <tr>
                     <td colspan="3" className="final-price">Kembalian</td>
                     <td className="final-price">{rupiah(detail.jumlah_bayar - (harga_jual - (diskon + detail.diskon_pembayaran)))}</td>
                  </tr>
               </table>
               <div style={{ marginTop: 10 }}>{detail.terbilang}</div>
            </div>
            <div className="thanks">~~~ Terima Kasih ~~~</div>
            <div className="azost">www.azostech.com</div>
         </>
      )
   }
}

ReactDOM.render(<CetakStruk />, document.getElementById('root'))