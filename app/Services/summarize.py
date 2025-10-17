import json
import sys
from transformers import BartTokenizer, BartForConditionalGeneration
import torch

class BARTSummarizer:
    def __init__(self):
        self.model = BartForConditionalGeneration.from_pretrained('facebook/bart-large-cnn')
        self.tokenizer = BartTokenizer.from_pretrained('facebook/bart-large-cnn')
        self.device = torch.device("cuda" if torch.cuda.is_available() else "cpu")
        self.model.to(self.device)
    
    def summarize(self, text):
        inputs = self.tokenizer.encode_plus(
            text,
            return_tensors='pt',
            max_length=4096,    # 1024
            truncation=True
        )
        
        inputs['input_ids'] = inputs['input_ids'].to(self.device)
        inputs['attention_mask'] = inputs['attention_mask'].to(self.device)
        
        outputs = self.model.generate(
            inputs['input_ids'],
            num_beams=4,           # 4
            length_penalty=1.0,    # 2.0
            min_length=30,
            max_length=240         # 140
        )
        
        return self.tokenizer.decode(outputs[0], skip_special_tokens=True)

if __name__ == "__main__":
    import sys
    if len(sys.argv) != 2:
        print("Usage: python summarize.py <input_json_file>")
        sys.exit(1)
    
    input_file = sys.argv[1]
    with open(input_file, 'r') as f:
        data = json.load(f)
    
    summarizer = BARTSummarizer()
    result = summarizer.summarize(data['text'])
    
    # Write result to new file
    result_file = input_file + '.result'
    with open(result_file, 'w') as f:
        json.dump({'summary': result}, f)
