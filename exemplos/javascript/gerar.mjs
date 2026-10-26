// Node 18+: baixa um EAN-13 e um QR Code em PNG.
// Uso: node gerar.mjs
import { writeFile } from 'node:fs/promises'

const API = 'https://www.geracodigo.com.br/api/v1'

async function baixar(url, arquivo) {
  const res = await fetch(url)
  if (!res.ok) {
    // Erro vem em JSON: { "erro": "...", "dica": "..." }
    const { erro, dica } = await res.json()
    throw new Error([erro, dica].filter(Boolean).join(' '))
  }
  await writeFile(arquivo, Buffer.from(await res.arrayBuffer()))
  console.log('salvo', arquivo)
}

const ean = new URLSearchParams({ format: 'ean13', data: '789123456789' })
await baixar(`${API}/barcode?${ean}`, 'ean13.png')

const qr = new URLSearchParams({ data: 'https://exemplo.com.br', size: '400' })
await baixar(`${API}/qr?${qr}`, 'qrcode.png')
