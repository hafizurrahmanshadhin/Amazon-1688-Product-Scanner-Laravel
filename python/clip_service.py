from fastapi import FastAPI, Body
from pydantic import BaseModel
from typing import List
import torch
import clip
from PIL import Image
import requests
from io import BytesIO

app = FastAPI()
device = "cpu"
model, preprocess = clip.load("ViT-B/32", device=device)

class EmbedRequest(BaseModel):
    images: List[str] = []
    texts: List[str] = []

@app.get("/")
def health():
    return {"status": "ok", "endpoint": "/embed", "method": "POST"}

@app.post("/embed")
def embed(req: EmbedRequest):
    outputs = {"image_embeddings": [], "text_embeddings": []}

    if req.images:
        imgs = []
        for url in req.images:
            try:
                resp = requests.get(url, timeout=8)
                img = preprocess(Image.open(BytesIO(resp.content)).convert("RGB"))
                imgs.append(img)
            except:
                pass
        if imgs:
            batch = torch.stack(imgs).to(device)
            with torch.no_grad():
                img_feat = model.encode_image(batch)
            img_feat /= img_feat.norm(dim=-1, keepdim=True)
            outputs["image_embeddings"] = img_feat.cpu().tolist()

    if req.texts:
        tokens = clip.tokenize(req.texts).to(device)
        with torch.no_grad():
            text_feat = model.encode_text(tokens)
        text_feat /= text_feat.norm(dim=-1, keepdim=True)
        outputs["text_embeddings"] = text_feat.cpu().tolist()

    return outputs
