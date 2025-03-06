// Import required modules
const express = require('express');
const mongoose = require('mongoose');
const multer = require('multer');
const admin = require('firebase-admin');
const { v4: uuidv4 } = require('uuid');
require('dotenv').config();

// Firebase Admin SDK
const serviceAccount = require('./firebase-admin.json');
admin.initializeApp({
  credential: admin.credential.cert(serviceAccount),
  storageBucket: process.env.FIREBASE_BUCKET
});
const bucket = admin.storage().bucket();

// MongoDB Connection
mongoose.connect(process.env.MONGO_URI, {
  useNewUrlParser: true,
  useUnifiedTopology: true,
});

const ProductSchema = new mongoose.Schema({
  name: String,
  price: Number,
  image: String,
  description: String,
});

const Product = mongoose.model('Product', ProductSchema);

// Express App
const app = express();
app.use(express.json());

// Multer Setup
const storage = multer.memoryStorage();
const upload = multer({ storage: storage });

// Add Product API
app.post('/add-product', upload.single('productImage'), async (req, res) => {
  try {
    const { productName, productPrice, productDescription } = req.body;
    const file = req.file;
    const fileName = `${uuidv4()}_${file.originalname}`;
    const fileUpload = bucket.file(fileName);

    await fileUpload.save(file.buffer, { contentType: file.mimetype });
    const publicUrl = `https://storage.googleapis.com/${bucket.name}/${fileName}`;

    const product = new Product({
      name: productName,
      price: productPrice,
      image: publicUrl,
      description: productDescription,
    });

    await product.save();
    res.status(201).json({ message: 'Product Added Successfully' });
  } catch (error) {
    res.status(500).json({ error: 'Failed to Add Product' });
  }
});

// Fetch Products API
app.get('/products', async (req, res) => {
  try {
    const products = await Product.find();
    res.status(200).json(products);
  } catch (error) {
    res.status(500).json({ error: 'Failed to Fetch Products' });
  }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));
